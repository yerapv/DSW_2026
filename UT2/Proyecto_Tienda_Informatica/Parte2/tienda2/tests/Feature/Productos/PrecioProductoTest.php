<?php

namespace Tests\Feature\Productos;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PrecioProductoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function datos(mixed $precio): array
    {
        return [
            'nombre' => 'Producto de prueba', 'marca' => 'Nexo', 'anio' => (int) date('Y'),
            'categoria_id' => Categoria::firstOrCreate(['nombre' => 'Periféricos'])->id,
            'tipo' => 2, 'precio' => $precio,
            'imagen' => UploadedFile::fake()->image('producto.jpg'),
        ];
    }

    public static function preciosValidos(): array
    {
        return [
            'coma' => ['24,50', 2450],
            'segundo ejemplo' => ['25,65', 2565],
            'punto' => ['24.50', 2450],
            'sin decimales' => ['24', 2400],
            'un decimal' => ['24,5', 2450],
            'mínimo' => ['0,01', 1],
            'céntimos exactos' => ['0,29', 29],
            'sin redondeos' => ['19,99', 1999],
            'máximo' => ['100000,00', 10000000],
            'espacios exteriores' => [' 25,65 ', 2565],
        ];
    }

    #[DataProvider('preciosValidos')]
    public function test_alta_convierte_euros_en_centimos_exactos(string $precio, int $centimos): void
    {
        $datos = $this->datos($precio);
        // Un dato interno enviado desde el navegador no puede alterar el importe.
        $datos['precio_centimos'] = 1;
        $this->post('/admin/productos', $datos)->assertRedirect('/admin/productos');
        $this->assertSame($centimos, Producto::firstOrFail()->precio_centimos);
    }

    public static function preciosInvalidos(): array
    {
        return [
            'vacío' => [''],
            'nulo' => [null],
            'cero' => ['0,00'],
            'negativo' => ['-24,50'],
            'más de dos decimales' => ['24,501'],
            'superior al máximo' => ['100000,01'],
            'dos separadores' => ['1.234,56'],
            'notación científica' => ['1e2'],
            'texto' => ['veinticinco'],
            'símbolo de moneda' => ['24,50 €'],
            'varias comas' => ['24,,50'],
            'array' => [['24,50']],
        ];
    }

    #[DataProvider('preciosInvalidos')]
    public function test_precio_invalido_no_guarda_producto_ni_fotografia(mixed $precio): void
    {
        $this->post('/admin/productos', $this->datos($precio))->assertSessionHasErrors('precio');
        $this->assertDatabaseCount('productos', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_edicion_muestra_y_guarda_precio_con_coma(): void
    {
        $this->post('/admin/productos', $this->datos('24,50'))->assertRedirect('/admin/productos');
        $producto = Producto::firstOrFail();
        $url = '/admin/productos/'.$producto->id;
        $this->get($url.'/edit')->assertOk()->assertSee('value="24,50"', false)
            ->assertSee('name="precio"', false)->assertDontSee('name="precio_centimos"', false);

        $datos = $this->datos('25,65');
        unset($datos['imagen']);
        $this->put($url, $datos)->assertRedirect('/admin/productos');
        $this->assertSame(2565, $producto->fresh()->precio_centimos);
        $this->assertSame($producto->ruta_imagen, $producto->fresh()->ruta_imagen);
        $this->get($url.'/edit')->assertSee('value="25,65"', false);
        $this->get('/admin/productos')->assertSee('25,65 €');
    }

    public function test_error_en_otro_campo_conserva_el_precio_introducido(): void
    {
        $datos = $this->datos('25,65');
        $datos['nombre'] = '';
        $this->from('/admin/productos/create')->post('/admin/productos', $datos)
            ->assertRedirect('/admin/productos/create')->assertSessionHasErrors('nombre')
            ->assertSessionHasInput('precio', '25,65');
        $this->assertDatabaseCount('productos', 0);
    }
}
