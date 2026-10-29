<?php

namespace Tests\Feature\Navegacion;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Comprueba la separación de navegación, vistas y rutas sin introducir login.
class SeparacionAreasTest extends TestCase
{
    use RefreshDatabase;

    /** Los tres accesos de la derecha están disponibles sin una sesión de usuario. */
    public function test_accesos_usuario_admin_y_carrito_sin_login(): void
    {
        $this->get('/usuario')->assertOk()->assertViewIs('usuario.index')
            ->assertSeeInOrder(['>Usuario</a>', '>Admin</a>', '>Carrito</a>'], false);
        $this->get('/admin')->assertOk()->assertViewIs('admin.index')
            ->assertSee('Panel de administración');
        $this->get('/carrito')->assertOk()->assertViewIs('usuario.carrito.index');
    }

    /** La tienda ofrece compra; administración conserva las acciones del CRUD. */
    public function test_vistas_distintas_para_catalogo_publico_y_gestion(): void
    {
        $this->seed();
        $producto = Producto::firstOrFail();
        $this->get('/productos')->assertOk()->assertViewIs('usuario.productos.index')
            ->assertSee('Añadir al carrito')->assertDontSee('Nuevo producto')
            ->assertDontSee('Editar')->assertDontSee('Eliminar');
        $this->get('/productos/'.$producto->id)->assertOk()->assertViewIs('usuario.productos.show')
            ->assertSee('Añadir al carrito')->assertDontSee('Editar');
        $this->get('/admin/productos')->assertOk()->assertViewIs('admin.productos.index')
            ->assertSee('Nuevo producto')->assertSee('Editar')->assertSee('Eliminar');
        $this->get('/admin/productos/'.$producto->id)->assertOk()->assertViewIs('admin.productos.show');
    }

    /** Publicar y subir imágenes son acciones de las pantallas de administración. */
    public function test_avisos_y_escaparate_publicos_sin_controles_de_gestion(): void
    {
        $this->get('/avisos')->assertOk()->assertViewIs('usuario.avisos.index')
            ->assertDontSee('Nuevo aviso');
        $this->get('/imagenes')->assertOk()->assertViewIs('usuario.imagenes.index')
            ->assertDontSee('Subir fotografía')->assertDontSee('Eliminar');
        $this->get('/admin/avisos')->assertOk()->assertViewIs('admin.avisos.index')
            ->assertSee('Nuevo aviso');
        $this->get('/admin/imagenes')->assertOk()->assertViewIs('admin.imagenes.index')
            ->assertSee('Subir fotografía');
    }

    /** La escritura del catálogo solo tiene rutas bajo /admin, aunque el acceso es libre. */
    public function test_rutas_publicas_de_productos_solo_permiten_lectura(): void
    {
        $this->seed();
        $producto = Producto::firstOrFail();
        $this->post('/productos', [])->assertStatus(405);
        $this->put('/productos/'.$producto->id, [])->assertStatus(405);
        $this->delete('/productos/'.$producto->id)->assertStatus(405);
        $this->assertDatabaseHas('productos', ['id' => $producto->id]);
    }

    /** EXPLORA es horizontal y TU ESPACIO solo se muestra al abrir el botón Usuario. */
    public function test_menu_horizontal_y_espacio_exclusivo_de_usuario(): void
    {
        $this->get('/')->assertOk()->assertSee('EXPLORA '.config('tienda.nombre'))
            ->assertDontSee('<aside', false)->assertDontSee('TU ESPACIO');
        $this->get('/usuario')->assertOk()->assertSee('TU ESPACIO')
            ->assertSee('Tarifas de envío')->assertSee('Simular financiación')
            ->assertDontSee('<aside', false);
        $this->get('/montaje')->assertOk()->assertDontSee('TU ESPACIO');
        $this->get('/carrito')->assertOk()->assertDontSee('TU ESPACIO')
            ->assertDontSee('<aside', false);
        $this->get('/admin')->assertOk()->assertDontSee('TU ESPACIO')
            ->assertDontSee('<aside', false);
    }
}
