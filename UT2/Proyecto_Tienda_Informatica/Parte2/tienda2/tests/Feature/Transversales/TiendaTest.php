<?php

namespace Tests\Feature\Transversales;

use App\Enums\TipoProducto;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// Las pruebas Feature recorren la ruta, el Controller, Eloquent y Blade juntos.
class TiendaTest extends TestCase
{
    // Como en los tests de clase, la BD de pruebas se reconstruye de forma aislada.
    use RefreshDatabase;

    /** El seeder permite recorrer todas las pantallas y sus relaciones. */
    public function test_paginas_de_los_ejemplos_y_binding(): void
    {
        $this->seed();
        foreach (['/', '/usuario', '/admin', '/admin/productos', '/admin/avisos', '/admin/imagenes', '/novedades', '/envios',
            '/ficha-cliente', '/avisos', '/admin/avisos/create',
            '/productos', '/admin/productos/create', '/categorias', '/montaje',
            '/financiacion', '/imagenes', '/admin/imagenes/create', '/carrito'] as $url) {
            $this->get($url)->assertOk();
        }
        $producto = Producto::firstOrFail();
        $this->get('/productos/'.$producto->id)->assertOk()->assertSee($producto->nombre);
        $this->get('/admin/productos/'.$producto->id.'/edit')->assertOk();
        $this->get('/productos/999999')->assertNotFound();
    }

    /** El filtro se aplica al array antes de construir la vista. */
    public function test_filtro_get_del_array(): void
    {
        $this->get('/novedades?tipo=periferico')->assertOk()
            ->assertSee('Teclado compacto')->assertDontSee('Portátil ligero');
    }

    /** El FormRequest bloquea tipos ajenos a los previstos. */
    public function test_ficha_con_form_request(): void
    {
        $this->post('/ficha-cliente', ['nombre' => 'Ana', 'email' => 'ana@example.test', 'edad' => 20, 'tipo' => 'otro'])
            ->assertSessionHasErrors('tipo');
        $this->post('/ficha-cliente', ['nombre' => 'Ana', 'email' => 'ana@example.test', 'edad' => 20, 'tipo' => 'estudiante'])
            ->assertRedirect();
        $this->assertDatabaseHas('clientes', ['nombre' => 'Ana', 'tipo' => 'estudiante', 'edad' => 20, 'email' => 'ana@example.test']);
    }

    /** El aviso sí persiste y aparece después en el listado. */
    public function test_primera_persistencia_del_aviso(): void
    {
        $this->post('/admin/avisos', ['titulo' => 'Horario nuevo', 'contenido' => 'Abrimos a las nueve.'])->assertRedirect('/admin/avisos');
        $this->assertDatabaseHas('avisos', ['titulo' => 'Horario nuevo']);
        $this->get('/avisos')->assertSee('Abrimos a las nueve.');
    }

    /** Alta con FK, cast enum y un JSON dependiente del tipo. */
    public function test_crud_producto_y_casts(): void
    {
        Storage::fake('public');
        $categoria = Categoria::create(['nombre' => 'Portátiles']);
        $datos = ['nombre' => 'Equipo de prueba', 'marca' => 'Nexo', 'anio' => 2026,
            'precio' => '400,00', 'categoria_id' => $categoria->id, 'tipo' => 1,
            'ram_gb' => 16, 'conexion' => 'Este campo no corresponde', 'disponible' => 1,
            'imagen' => UploadedFile::fake()->image('portatil.jpg')];
        $this->post('/admin/productos', $datos)->assertRedirect('/admin/productos');
        $producto = Producto::firstOrFail();
        $this->assertSame(TipoProducto::Portatil, $producto->tipo);
        $this->assertEquals(['ram_gb' => 16], $producto->caracteristicas);
        $this->assertTrue($producto->disponible);
        $this->assertSame('Portátiles', $producto->categoria->nombre);

        // Al editar sin enviar el checkbox, prepareForValidation lo convierte a false.
        unset($datos['disponible'], $datos['imagen']);
        $datos['nombre'] = 'Equipo actualizado';
        $this->put('/admin/productos/'.$producto->id, $datos)->assertRedirect('/admin/productos');
        $this->assertFalse($producto->fresh()->disponible);
        $this->get('/productos/'.$producto->id)->assertSee('Equipo actualizado');
        $this->delete('/admin/productos/'.$producto->id)->assertRedirect('/admin/productos');
        $this->assertDatabaseCount('productos', 0);
    }

    /** Los datos inválidos no llegan al INSERT. */
    public function test_producto_rechaza_campos_invalidos(): void
    {
        $this->post('/admin/productos', ['nombre' => 'Producto', 'categoria_id' => 999, 'tipo' => 'inventado', 'precio' => '-1'])
            ->assertSessionHasErrors(['marca', 'anio', 'categoria_id', 'tipo', 'precio']);
        $this->assertDatabaseCount('productos', 0);
    }

    /** La consulta conserva el filtro en los enlaces de la segunda página. */
    public function test_filtros_y_paginacion(): void
    {
        $this->seed();
        $this->get('/productos?q=Portátil&tipo=1')->assertOk()
            ->assertViewHas('productos', fn ($productos) => $productos->total() === 3)
            ->assertSee('Portátil Aula 15')->assertDontSee('Teclado USB');
        $this->get('/productos?tipo=2')->assertViewHas('productos', fn ($p) => count($p) === 5 && $p->total() === 5);
        $this->get('/productos?q=a')->assertSee('q=a', false)->assertSee('page=2', false);
        $this->get('/productos?page=2')->assertViewHas('productos', fn ($p) => count($p) === 5);
    }

    /** Se comprueban también las respuestas HTTP de los servicios. */
    public function test_formularios_de_servicios(): void
    {
        $this->post('/montaje', ['equipos' => 2, 'instalacion' => 1, 'urgente' => 1])
            ->assertOk()->assertSee('85,00');
        $this->post('/financiacion', ['edad' => 20, 'importe' => 1000, 'antiguedad_meses' => 2, 'documentacion_completa' => 1])
            ->assertOk()->assertSee('Solicitud válida')->assertSee('antigüedad es baja');
    }
}
