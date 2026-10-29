<?php

namespace Tests\Feature\Clientes;

use App\Enums\EstadoPedido;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EspacioClienteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_cada_cliente_tiene_su_espacio_sin_recordar_la_seleccion_en_sesion(): void
    {
        $ana = Cliente::firstOrFail();
        $otro = Cliente::whereKeyNot($ana->id)->firstOrFail();
        $this->post('/carrito/lineas', ['cliente_id' => $ana->id, 'producto_id' => Producto::firstOrFail()->id, 'cantidad' => 3]);
        $this->post('/carrito/presupuesto', ['cliente_id' => $ana->id])->assertRedirect();
        $this->get('/usuario?cliente_id='.$ana->id)->assertOk()->assertViewHas('cliente', fn ($seleccionado) => $seleccionado->id === $ana->id)
            ->assertSee('3 unidades en el carrito')->assertSee('1 presupuestos guardados')->assertDontSee('← Volver')
            ->assertSee('href="'.route('carrito.index', ['cliente_id' => $ana->id]).'"', false)
            ->assertSee('href="'.route('pedidos.index', ['cliente_id' => $ana->id]).'"', false);
        $this->get('/usuario?cliente_id='.$otro->id)->assertOk()->assertViewHas('cliente', fn ($seleccionado) => $seleccionado->id === $otro->id)
            ->assertSee('0 unidades en el carrito')->assertSee('0 presupuestos guardados');
        $this->get('/usuario')->assertOk()->assertViewHas('cliente', null)->assertSee('Abrir Tu Espacio');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_los_enlaces_y_formularios_conservan_el_cliente_y_la_compra_redirige_a_su_espacio(): void
    {
        $cliente = Cliente::firstOrFail();
        $id = $cliente->id;
        foreach (['/productos', '/categorias', '/novedades', '/montaje', '/ficha-cliente', '/financiacion'] as $url) {
            $this->get($url.'?cliente_id='.$id)->assertOk()
                ->assertSee('href="'.route('usuario.index', ['cliente_id' => $id]).'"', false)
                ->assertSee('href="'.route('carrito.index', ['cliente_id' => $id]).'"', false);
        }
        $this->get('/novedades?cliente_id='.$id)->assertSee('name="cliente_id" value="'.$id.'"', false);
        $this->post('/carrito/lineas', ['cliente_id' => $id, 'producto_id' => Producto::firstOrFail()->id, 'cantidad' => 1]);
        $this->post('/carrito/pagar', ['cliente_id' => $id, 'metodo_pago' => 'tarjeta'])
            ->assertRedirect('/pedidos/'.Pedido::firstOrFail()->id.'?cliente_id='.$id);
    }

    public function test_historico_personal_filtra_y_el_compartido_conserva_todos_los_pedidos(): void
    {
        $clientes = Cliente::take(2)->get();
        foreach ($clientes as $cliente) {
            for ($i = 0; $i < 6; $i++) {
                Pedido::create(['cliente_id' => $cliente->id, 'total_centimos' => 100,
                    'instantanea' => [], 'metodo_pago' => 'tarjeta', 'estado' => EstadoPedido::EnPreparacion]);
            }
        }
        $this->get('/pedidos?cliente_id='.$clientes[0]->id)->assertOk()
            ->assertViewHas('pedidos', fn ($pedidos) => $pedidos->total() === 6 && $pedidos->every(fn ($pedido) => $pedido->cliente_id === $clientes[0]->id))
            ->assertSee('cliente_id='.$clientes[0]->id, false)->assertSee('page=2', false);
        $this->get('/pedidos')->assertOk()->assertViewHas('pedidos', fn ($pedidos) => $pedidos->total() === 12);
        $this->get('/usuario?cliente_id=999999')->assertSessionHasErrors('cliente_id');
        $this->get('/pedidos?cliente_id=incorrecto')->assertSessionHasErrors('cliente_id');
    }
}
