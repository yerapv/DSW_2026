<?php

namespace Tests\Feature;

use App\Enums\TipoProducto;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdministracionTest extends TestCase
{
    use RefreshDatabase;

    public function test_panel_y_formularios_de_administracion(): void
    {
        $this->seed();
        $this->get('/')->assertRedirect('/admin');

        foreach (['/admin', '/admin/productos', '/admin/productos/create', '/admin/avisos',
            '/admin/avisos/nuevo', '/admin/imagenes', '/admin/imagenes/nueva'] as $url) {
            $this->get($url)->assertOk()->assertDontSee('TU ESPACIO')
                ->assertDontSee('>Usuario</a>', false)->assertDontSee('>Carrito</a>', false);
        }

        $producto = Producto::firstOrFail();
        $this->get('/admin/productos/'.$producto->id)->assertOk()->assertSee($producto->nombre);
        $this->get('/admin/productos/'.$producto->id.'/edit')->assertOk();
        $this->get('/admin/productos/999999')->assertNotFound();
    }

    public function test_no_existen_modulos_publicos_ni_tablas_de_compra(): void
    {
        foreach (['/usuario', '/productos', '/avisos', '/imagenes', '/clientes',
            '/categorias', '/carrito', '/pedidos', '/montaje', '/boletin', '/financiacion'] as $url) {
            $this->get($url)->assertNotFound();
        }

        foreach (['clientes', 'cliente_producto', 'carritos', 'lineas_carrito', 'presupuestos', 'pedidos'] as $tabla) {
            $this->assertFalse(Schema::hasTable($tabla));
        }
    }

    public function test_datos_iniciales_no_se_duplican(): void
    {
        $this->seed();
        $this->seed();
        $this->assertDatabaseCount('categorias', 3);
        $this->assertDatabaseCount('productos', 12);
        $this->assertDatabaseCount('avisos', 1);
    }

    public function test_crud_categoria_enum_json_y_disponibilidad(): void
    {
        Storage::fake('public');
        $categoria = Categoria::create(['nombre' => 'Portátiles']);
        $datos = [
            'nombre' => 'Portátil de prueba', 'marca' => 'Nexo', 'anio' => (int) date('Y'),
            'categoria_id' => $categoria->id, 'precio' => '649,50',
            'tipo' => 'portatil', 'ram_gb' => 16, 'conexion' => 'USB', 'disponible' => 1,
            'imagen' => UploadedFile::fake()->image('portatil.jpg'),
        ];
        $this->post('/admin/productos', $datos)->assertRedirect('/admin/productos')->assertSessionMissing('ok');
        $producto = Producto::firstOrFail();
        $this->assertSame(TipoProducto::Portatil, $producto->tipo);
        $this->assertEquals(['ram_gb' => 16], $producto->caracteristicas);
        $this->assertTrue($producto->disponible);
        $this->assertSame('Portátiles', $producto->categoria->nombre);

        unset($datos['imagen'], $datos['disponible']);
        $datos['nombre'] = 'Portátil actualizado';
        $this->put('/admin/productos/'.$producto->id, $datos)->assertRedirect('/admin/productos');
        $this->assertFalse($producto->fresh()->disponible);
        $this->get('/admin/productos/'.$producto->id)->assertSee('Portátil actualizado');
        $this->delete('/admin/productos/'.$producto->id)->assertRedirect('/admin/productos');
        $this->assertDatabaseCount('productos', 0);
    }

    public function test_validacion_conserva_datos_y_explica_errores(): void
    {
        $this->from('/admin/productos/create')->post('/admin/productos', [
            'nombre' => 'Producto de prueba', 'categoria_id' => 999,
            'tipo' => 'inventado', 'precio' => '-1',
        ])->assertRedirect('/admin/productos/create')
            ->assertSessionHasErrors([
                'marca' => 'Indica la marca del producto.',
                'anio', 'categoria_id', 'tipo', 'precio', 'imagen',
            ])
            ->assertSessionHasInput('nombre', 'Producto de prueba');
        $this->assertDatabaseCount('productos', 0);
    }

    public function test_busqueda_filtro_y_paginacion_conservan_los_parametros(): void
    {
        $this->seed();
        $this->get('/admin/productos?q=Portátil&tipo=portatil')->assertOk()
            ->assertViewHas('productos', fn ($p) => $p->total() === 3)
            ->assertSee('Portátil Aula 15')->assertDontSee('Teclado USB');
        $this->get('/admin/productos?tipo=periferico')->assertOk()
            ->assertViewHas('productos', fn ($p) => $p->count() === 5 && $p->total() === 5);
        $this->get('/admin/productos?q=a')->assertOk()->assertSee('q=a', false)->assertSee('page=2', false);
        $this->get('/admin/productos?page=2')->assertOk()
            ->assertViewHas('productos', fn ($p) => $p->count() === 5);
    }

    public function test_publicar_y_validar_avisos(): void
    {
        $this->post('/admin/avisos', ['titulo' => '', 'contenido' => ''])
            ->assertSessionHasErrors(['titulo', 'contenido']);
        $this->post('/admin/avisos', ['titulo' => 'Horario nuevo', 'contenido' => 'Abrimos a las nueve.'])
            ->assertRedirect('/admin/avisos')->assertSessionMissing('ok');
        $this->assertDatabaseHas('avisos', ['titulo' => 'Horario nuevo']);
        $this->get('/admin/avisos')->assertOk()->assertSee('Abrimos a las nueve.');
    }
}
