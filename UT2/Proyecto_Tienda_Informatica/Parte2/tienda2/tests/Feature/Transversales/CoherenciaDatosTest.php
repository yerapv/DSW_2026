<?php

namespace Tests\Feature\Transversales;

use App\Models\Cliente;
use App\Models\MetodoPago;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CoherenciaDatosTest extends TestCase
{
    use RefreshDatabase;

    public static function edadesInvalidas(): array
    {
        return ['300 años' => [300], 'menos 30' => [-30], 'superior al límite' => [121],
            'negativa' => [-1], 'decimal' => [25.5], 'texto' => ['veinte'], 'lista' => [[20]]];
    }

    #[DataProvider('edadesInvalidas')]
    public function test_edad_invalida_no_se_guarda_desde_admin_ni_desde_la_ficha(mixed $edad): void
    {
        $original = ['nombre' => 'Cliente original', 'email' => 'original@example.test', 'tipo' => 'particular', 'edad' => 35];
        $cliente = Cliente::create($original);
        $datos = ['nombre' => 'Nombre manipulado', 'email' => 'nuevo@example.test', 'tipo' => 'empresa', 'edad' => $edad];
        $this->post('/admin/clientes', $datos)->assertSessionHasErrors('edad');
        $this->put('/admin/clientes/'.$cliente->id, $datos)->assertSessionHasErrors('edad');
        $this->post('/ficha-cliente', $datos)->assertSessionHasErrors('edad');
        $this->post('/ficha-cliente?cliente_id='.$cliente->id, $datos)->assertSessionHasErrors('edad');
        $this->assertDatabaseCount('clientes', 1);
        $this->assertDatabaseHas('clientes', $original + ['id' => $cliente->id]);
    }

    public function test_edades_limite_se_guardan_y_admin_permite_edad_sin_indicar(): void
    {
        foreach ([0, 120] as $edad) {
            $datos = ['nombre' => 'Cliente '.$edad, 'email' => 'cliente'.$edad.'@example.test', 'tipo' => 'particular', 'edad' => $edad];
            $this->post('/admin/clientes', $datos)->assertRedirect();
            $cliente = Cliente::where('email', $datos['email'])->firstOrFail();
            $this->post('/ficha-cliente?cliente_id='.$cliente->id, $datos)->assertRedirect();
            $this->assertSame($edad, $cliente->fresh()->edad);
        }
        $this->post('/admin/clientes', ['nombre' => 'Sin edad', 'email' => 'sin@example.test', 'edad' => null])->assertRedirect();
        $this->assertNull(Cliente::where('email', 'sin@example.test')->firstOrFail()->edad);
    }

    public static function financiacionIncoherente(): array
    {
        return [
            '300 años' => ['edad', 300], 'edad negativa' => ['edad', -30], 'edad decimal' => ['edad', 20.5],
            'edad como lista' => ['edad', [20]], 'antigüedad negativa' => ['antiguedad_meses', -1],
            'antigüedad decimal' => ['antiguedad_meses', 1.5], 'más trabajo que vida' => ['antiguedad_meses', 241],
            'antigüedad enorme' => ['antiguedad_meses', 999999], 'importe negativo' => ['importe', -1],
            'fracciones de céntimo' => ['importe', '24.555'], 'importe como lista' => ['importe', [1000]],
            'documentación inválida' => ['documentacion_completa', 'incorrecto'],
        ];
    }

    #[DataProvider('financiacionIncoherente')]
    public function test_financiacion_rechaza_datos_incoherentes(string $campo, mixed $valor): void
    {
        $datos = ['edad' => 20, 'antiguedad_meses' => 12, 'importe' => '1000.00', 'documentacion_completa' => 1];
        $datos[$campo] = $valor;
        $this->post('/financiacion', $datos)->assertSessionHasErrors($campo);
    }

    public function test_financiacion_distingue_formato_valido_de_reglas_de_negocio(): void
    {
        foreach ([0, 120] as $edad) {
            $this->post('/financiacion', ['edad' => $edad, 'antiguedad_meses' => 0, 'importe' => 0, 'documentacion_completa' => 1])->assertOk();
        }
        $datos = ['edad' => 20, 'antiguedad_meses' => 12, 'documentacion_completa' => 1];
        $this->post('/financiacion', $datos + ['importe' => 3000])->assertOk()->assertViewHas('resultado', fn ($r) => $r['valid']);
        $this->post('/financiacion', $datos + ['importe' => 3001])->assertOk()->assertViewHas('resultado', fn ($r) => ! $r['valid']);
    }

    public static function productosInvalidos(): array
    {
        return ['nombre vacío' => ['nombre', ''], 'marca larga' => ['marca', str_repeat('a', 121)],
            'año antiguo' => ['anio', 1999], 'año futuro' => ['anio', (int) date('Y') + 1],
            'precio cero' => ['precio', '0'], 'precio negativo' => ['precio', '-1'], 'precio impreciso' => ['precio', '24,555'],
            'tipo inexistente' => ['tipo', 999], 'categorías múltiples' => ['categoria_id', [1, 2]],
            'disponibilidad falsa' => ['disponible', 'incorrecto'], 'ram cero' => ['ram_gb', 0],
            'ram excesiva' => ['ram_gb', 257], 'conexión larga' => ['conexion', str_repeat('a', 81)]];
    }

    #[DataProvider('productosInvalidos')]
    public function test_producto_invalido_no_se_crea_ni_modifica(string $campo, mixed $valor): void
    {
        Storage::fake('public');
        $this->seed();
        $producto = Producto::firstOrFail();
        $original = $producto->getAttributes();
        $cantidad = Producto::count();
        $datos = ['nombre' => 'Producto nuevo', 'marca' => 'Marca', 'anio' => (int) date('Y'),
            'categoria_id' => $producto->categoria_id, 'tipo' => 1, 'precio' => '24,50',
            'ram_gb' => 16, 'disponible' => 1, 'imagen' => UploadedFile::fake()->image('foto.png')];
        $datos[$campo] = $valor;
        $this->post('/admin/productos', $datos)->assertSessionHasErrors($campo);
        $this->put('/admin/productos/'.$producto->id, $datos)->assertSessionHasErrors($campo);
        $this->assertSame($cantidad, Producto::count());
        $this->assertSame($original, $producto->fresh()->getAttributes());
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public static function cantidadesInvalidas(): array
    {
        return ['cero' => [0], 'negativa' => [-1], 'excesiva' => [101], 'decimal' => [1.5], 'texto' => ['dos'], 'lista' => [[2]]];
    }

    #[DataProvider('cantidadesInvalidas')]
    public function test_carrito_y_montaje_rechazan_cantidades_invalidas(mixed $cantidad): void
    {
        $this->seed();
        $this->post('/carrito/lineas', ['cliente_id' => Cliente::firstOrFail()->id,
            'producto_id' => Producto::firstOrFail()->id, 'cantidad' => $cantidad])->assertSessionHasErrors('cantidad');
        $this->post('/montaje', ['equipos' => $cantidad])->assertSessionHasErrors('equipos');
        $this->assertDatabaseCount('lineas_carrito', 0);
    }

    public function test_identificadores_y_busquedas_no_admiten_listas(): void
    {
        $this->seed();
        $cliente = Cliente::firstOrFail();
        $producto = Producto::firstOrFail();
        $this->post('/carrito/lineas', ['cliente_id' => $cliente->id, 'producto_id' => [$producto->id], 'cantidad' => 1])->assertSessionHasErrors('producto_id');
        $this->post('/carrito/lineas', ['cliente_id' => [$cliente->id], 'producto_id' => $producto->id, 'cantidad' => 1])->assertSessionHasErrors('cliente_id');
        foreach (['/productos', '/admin/productos'] as $url) {
            $this->get($url.'?q[]=equipo')->assertSessionHasErrors('q');
            $this->get($url.'?q='.str_repeat('a', 151))->assertSessionHasErrors('q');
        }
        $this->assertDatabaseCount('lineas_carrito', 0);
    }

    public function test_checkbox_y_campos_invalidos_de_gestion_no_modifican_registros(): void
    {
        $this->seed();
        $metodo = MetodoPago::firstOrFail();
        $original = $metodo->getAttributes();
        $datos = ['nombre' => 'Nuevo método', 'codigo' => 'nuevo', 'activo' => 'incorrecto'];
        $this->post('/admin/metodos-pago', $datos)->assertSessionHasErrors('activo');
        $this->put('/admin/metodos-pago/'.$metodo->id, $datos)->assertSessionHasErrors('activo');
        $this->assertSame($original, $metodo->fresh()->getAttributes());
        foreach (['nombre' => '', 'email' => 'incorrecto', 'tipo' => 'inexistente', 'direccion' => str_repeat('a', 256)] as $campo => $valor) {
            $cliente = Cliente::firstOrFail();
            $datos = ['nombre' => 'Otro nombre', 'email' => 'otro@example.test', 'tipo' => 'particular', 'edad' => 30];
            $datos[$campo] = $valor;
            $this->put('/admin/clientes/'.$cliente->id, $datos)->assertSessionHasErrors($campo);
            $this->post('/ficha-cliente?cliente_id='.$cliente->id, $datos)->assertSessionHasErrors($campo);
            $this->assertNotSame('Otro nombre', $cliente->fresh()->nombre);
        }
    }

    public function test_carrito_no_desborda_el_importe_almacenable_en_la_bd(): void
    {
        $this->seed();
        $cliente = Cliente::firstOrFail();
        $producto = Producto::firstOrFail();
        $producto->update(['precio_centimos' => 10000000]);
        $datos = ['cliente_id' => $cliente->id, 'producto_id' => $producto->id, 'cantidad' => 100];
        for ($i = 0; $i < 4; $i++) {
            $this->post('/carrito/lineas', $datos)->assertRedirect()->assertSessionHasNoErrors();
        }
        $this->post('/carrito/lineas', $datos)->assertSessionHasErrors('cantidad');
        $linea = $cliente->carrito->lineas->sole();
        $this->assertSame(400, $linea->cantidad);
        $this->assertSame(4000000000, $linea->precio_centimos);
    }
}
