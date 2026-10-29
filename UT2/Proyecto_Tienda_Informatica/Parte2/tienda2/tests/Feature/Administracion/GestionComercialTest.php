<?php

namespace Tests\Feature\Administracion;

use App\Enums\EstadoPedido;
use App\Models\Cliente;
use App\Models\MetodoPago;
use App\Models\Pedido;
use App\Models\Producto;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GestionComercialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function pedido(int $estado = 1): Pedido
    {
        $cliente = Cliente::firstOrFail();
        $metodo = MetodoPago::where('codigo', 'tarjeta')->firstOrFail();

        return Pedido::create([
            'cliente_id' => $cliente->id,
            'datos_cliente' => $cliente->only(['nombre', 'email', 'direccion']),
            'metodo_pago_id' => $metodo->id, 'metodo_pago' => $metodo->codigo,
            'metodo_pago_nombre' => $metodo->nombre, 'estado' => EstadoPedido::from($estado),
            'total_centimos' => 4900,
            'instantanea' => [['producto' => 'Producto histórico', 'cantidad' => 2, 'precio_centimos' => 4900]],
        ]);
    }

    public function test_crud_de_clientes_sin_cuentas_de_usuario(): void
    {
        $datos = ['nombre' => 'Nuevo cliente', 'email' => 'nuevo@example.test', 'direccion' => 'Calle de prueba, 1'];
        $this->post('/admin/clientes', $datos)->assertRedirect('/admin/clientes')->assertSessionMissing('ok');
        $cliente = Cliente::where('email', $datos['email'])->firstOrFail();
        $url = '/admin/clientes/'.$cliente->id;
        $this->get($url)->assertOk()->assertSee('nuevo@example.test');
        $this->get($url.'/edit')->assertOk()->assertSee('value="Nuevo cliente"', false);
        $datos['nombre'] = 'Cliente actualizado';
        $this->put($url, $datos)->assertRedirect('/admin/clientes');
        $this->assertSame('Cliente actualizado', $cliente->fresh()->nombre);
        $this->delete($url)->assertRedirect('/admin/clientes');
        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_cliente_invalido_no_se_guarda(): void
    {
        $this->post('/admin/clientes', ['nombre' => '', 'email' => 'incorrecto'])
            ->assertSessionHasErrors(['nombre', 'email']);
        $this->assertDatabaseCount('clientes', 3);
    }

    public function test_crud_de_metodos_y_desactivacion_en_el_pago(): void
    {
        $datos = ['nombre' => 'Efectivo simulado', 'codigo' => 'efectivo', 'activo' => 1];
        $this->post('/admin/metodos-pago', $datos)->assertRedirect('/admin/metodos-pago');
        $metodo = MetodoPago::where('codigo', 'efectivo')->firstOrFail();
        $url = '/admin/metodos-pago/'.$metodo->id;
        $this->get($url)->assertOk()->assertSee('Efectivo simulado');
        $this->get($url.'/edit')->assertOk()->assertSee('value="efectivo"', false);
        $this->post('/carrito/lineas', ['cliente_id' => Cliente::firstOrFail()->id, 'producto_id' => Producto::firstOrFail()->id, 'cantidad' => 1]);
        $this->get('/carrito/pago?cliente_id='.Cliente::firstOrFail()->id)->assertOk()->assertSee('Efectivo simulado');
        unset($datos['activo']);
        $this->put($url, $datos)->assertRedirect('/admin/metodos-pago');
        $this->assertFalse($metodo->fresh()->activo);
        $this->get('/carrito/pago?cliente_id='.Cliente::firstOrFail()->id)->assertOk()->assertDontSee('Efectivo simulado');
        $this->delete($url)->assertRedirect('/admin/metodos-pago');
        $this->assertDatabaseMissing('metodos_pago', ['id' => $metodo->id]);
    }

    public function test_codigos_de_pago_son_unicos_y_se_pueden_editar(): void
    {
        $this->post('/admin/metodos-pago', ['nombre' => 'Duplicado', 'codigo' => 'tarjeta', 'activo' => 1])
            ->assertSessionHasErrors('codigo');
        $metodo = MetodoPago::where('codigo', 'tarjeta')->firstOrFail();
        $this->put('/admin/metodos-pago/'.$metodo->id, ['nombre' => 'Tarjeta renombrada', 'codigo' => 'tarjeta', 'activo' => 1])
            ->assertRedirect('/admin/metodos-pago');
        $this->assertSame('Tarjeta renombrada', $metodo->fresh()->nombre);
    }

    public function test_administracion_no_permite_crear_ni_borrar_pedidos(): void
    {
        $pedido = $this->pedido();
        $this->get('/admin/pedidos/create')->assertNotFound();
        $this->post('/admin/pedidos', ['estado' => 1])->assertStatus(405);
        $this->delete('/admin/pedidos/'.$pedido->id)->assertStatus(405);
        $this->assertDatabaseCount('pedidos', 1);
        $this->get('/admin/pedidos')->assertOk()->assertDontSee('Nuevo pedido')->assertDontSee('Eliminar');
    }

    public function test_formulario_de_estado_se_envia_como_post_con_method_put(): void
    {
        $pedido = $this->pedido();
        $respuesta = $this->get('/admin/pedidos/'.$pedido->id.'/edit')->assertOk();
        $documento = new DOMDocument();
        @$documento->loadHTML($respuesta->getContent());
        $dom = new DOMXPath($documento);
        $formulario = $dom->query('//form[@method="POST"]')->item(0);
        $datos = ['estado' => EstadoPedido::Completado->value];
        foreach ($dom->query('.//input[@type="hidden"]', $formulario) as $campo) {
            $datos[$campo->getAttribute('name')] = $campo->getAttribute('value');
        }
        $this->assertSame('PUT', $datos['_method'] ?? null);
        $this->post($formulario->getAttribute('action'), $datos)->assertRedirect('/admin/pedidos');
        $this->assertSame(EstadoPedido::Completado, $pedido->fresh()->estado);
    }

    public function test_solo_cambia_estado_y_conserva_todos_los_datos_del_pedido(): void
    {
        $pedido = $this->pedido();
        $original = $pedido->fresh()->getAttributes();
        $otro = Cliente::create(['nombre' => 'Otro cliente', 'email' => 'otro@example.test']);
        foreach ([2, 3, 1, 4] as $estado) {
            $this->put('/admin/pedidos/'.$pedido->id, [
                'estado' => $estado, 'cliente_id' => $otro->id, 'metodo_pago' => 'transferencia',
                'total_centimos' => 1, 'instantanea' => [], 'datos_cliente' => [],
                'cantidades' => [Producto::firstOrFail()->id => 100],
            ])->assertRedirect('/admin/pedidos');
            $actual = $pedido->fresh()->getAttributes();
            $this->assertSame($estado, $actual['estado']);
            unset($actual['estado'], $actual['updated_at']);
            $esperado = $original;
            unset($esperado['estado'], $esperado['updated_at']);
            $this->assertSame($esperado, $actual);
        }
        $this->get('/admin/pedidos/'.$pedido->id.'/edit')->assertOk()
            ->assertSee('Cambiar estado')->assertDontSee('name="cliente_id"', false)
            ->assertDontSee('name="metodo_pago"', false);
    }

    public function test_borrar_cliente_y_metodo_conserva_el_historico(): void
    {
        $pedido = $this->pedido(2);
        $this->delete('/admin/clientes/'.$pedido->cliente_id)->assertRedirect('/admin/clientes');
        $this->delete('/admin/metodos-pago/'.$pedido->metodo_pago_id)->assertRedirect('/admin/metodos-pago');
        $actual = $pedido->fresh();
        $this->assertNull($actual->cliente_id);
        $this->assertNull($actual->metodo_pago_id);
        $this->assertEquals($pedido->datos_cliente, $actual->datos_cliente);
        $this->assertSame('Tarjeta simulada', $actual->nombreMetodoPago());
        $this->get('/admin/pedidos/historico')->assertOk()->assertSee($pedido->nombreCliente());
        $this->get('/pedidos/'.$pedido->id)->assertOk()->assertSee('Producto histórico')->assertSee('49,00');
    }

    public function test_pago_exige_cliente_y_admite_metodos_creados_en_admin(): void
    {
        $metodo = MetodoPago::create(['codigo' => 'otro', 'nombre' => 'Otro pago simulado', 'activo' => true]);
        $this->post('/carrito/lineas', ['cliente_id' => Cliente::firstOrFail()->id, 'producto_id' => Producto::firstOrFail()->id, 'cantidad' => 1]);
        $this->post('/carrito/pagar', ['metodo_pago' => $metodo->codigo])->assertSessionHasErrors('cliente_id');
        $this->assertDatabaseCount('pedidos', 0);
        $this->post('/carrito/pagar', ['cliente_id' => Cliente::firstOrFail()->id, 'metodo_pago' => 'otro'])->assertRedirect();
        $pedido = Pedido::firstOrFail();
        $this->assertSame('Otro pago simulado', $pedido->nombreMetodoPago());
        $this->assertNotNull($pedido->cliente_id);
        $this->assertDatabaseCount('lineas_carrito', 0);
    }

    public function test_pago_rechaza_metodo_inactivo_sin_vaciar_carrito(): void
    {
        MetodoPago::where('codigo', 'tarjeta')->update(['activo' => false]);
        $this->post('/carrito/lineas', ['cliente_id' => Cliente::firstOrFail()->id, 'producto_id' => Producto::firstOrFail()->id, 'cantidad' => 1]);
        $this->post('/carrito/pagar', ['cliente_id' => Cliente::firstOrFail()->id, 'metodo_pago' => 'tarjeta'])
            ->assertSessionHasErrors('metodo_pago');
        $this->assertDatabaseCount('pedidos', 0);
        $this->assertDatabaseCount('lineas_carrito', 1);
    }

    public function test_actualizacion_rechaza_estados_no_permitidos(): void
    {
        $pedido = $this->pedido();
        foreach (['inventado', 'pagado_simulado', 'enviado', 'entregado', ''] as $estado) {
            $this->put('/admin/pedidos/'.$pedido->id, ['estado' => $estado])->assertSessionHasErrors('estado');
            $this->assertSame(EstadoPedido::PendienteCobro, $pedido->fresh()->estado);
        }
        $this->delete('/admin/clientes/'.$pedido->cliente_id)->assertRedirect();
        $this->delete('/admin/metodos-pago/'.$pedido->metodo_pago_id)->assertRedirect();
        $this->put('/admin/pedidos/'.$pedido->id, ['estado' => 3])->assertRedirect('/admin/pedidos');
        $this->assertSame(EstadoPedido::Completado, $pedido->fresh()->estado);
    }

    public function test_historico_administrativo_filtra_y_pagina(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->pedido();
        }
        $this->pedido(3);
        $this->get('/admin/pedidos/historico?estado=1')->assertOk()
            ->assertViewHas('pedidos', fn ($p) => $p->total() === 6 && $p->count() === 5)
            ->assertSee('estado=1', false)->assertSee('page=2', false);
        $this->get('/admin/pedidos/historico?estado=3')->assertOk()
            ->assertViewHas('pedidos', fn ($p) => $p->total() === 1)->assertSee('Completado');
    }

    public function test_nuevas_pantallas_tienen_tablas_acciones_y_volver(): void
    {
        $pedido = $this->pedido();
        $registros = ['clientes' => Cliente::firstOrFail(), 'metodos-pago' => MetodoPago::firstOrFail()];
        foreach ($registros as $modulo => $registro) {
            $url = '/admin/'.$modulo;
            $this->get($url)->assertOk()->assertSee('<table', false)->assertSee('Editar')->assertSee('Eliminar')->assertSee('← Volver');
            foreach ([$url.'/create', $url.'/'.$registro->id, $url.'/'.$registro->id.'/edit'] as $pagina) {
                $this->get($pagina)->assertOk()->assertSee('back-button', false)->assertSee('← Volver')->assertDontSee('class="card', false);
            }
            $this->get($url.'/999999')->assertNotFound();
        }
        $this->get('/admin/pedidos')->assertOk()->assertSee('<table', false)->assertSee('Cambiar estado')->assertSee('← Volver');
        foreach (['/admin/pedidos/'.$pedido->id, '/admin/pedidos/'.$pedido->id.'/edit'] as $pagina) {
            $this->get($pagina)->assertOk()->assertSee('← Volver')->assertDontSee('Eliminar');
        }
        $this->get('/admin')->assertOk()->assertSee('Gestionar clientes')->assertSee('Gestionar pedidos')->assertSee('Histórico de pedidos');
    }
}
