<?php

namespace Tests\Feature\Pedidos;

use App\Enums\EstadoPedido;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Database\Seeders\MetodoPagoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

// Pago de demostración: se comprueba la persistencia y la integridad del carrito.
class PagoPedidoTest extends TestCase
{
    use RefreshDatabase;

    private Cliente $cliente;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MetodoPagoSeeder::class);
        $this->cliente = Cliente::create(['nombre' => 'Cliente de prueba', 'email' => 'cliente@example.test']);
    }

    /** Añade dos unidades al carrito usando el mismo POST que la tienda. */
    private function prepararSeleccion(): Producto
    {
        $categoria = Categoria::create(['nombre' => 'Periféricos']);
        $producto = Producto::create([
            'nombre' => 'Teclado histórico', 'marca' => 'Nexo', 'anio' => 2026,
            'precio_centimos' => 4000, 'tipo' => 2,
            'categoria_id' => $categoria->id, 'disponible' => true,
        ]);
        $this->post('/carrito/lineas', ['cliente_id' => $this->cliente->id, 'producto_id' => $producto->id, 'cantidad' => 2]);

        return $producto;
    }

    /** El pago calcula el importe en el servidor, guarda el pedido y vacía la selección. */
    public function test_pago_simulado_guarda_pedido_y_retira_lineas_compradas(): void
    {
        $this->prepararSeleccion();
        $this->get('/carrito/pago?cliente_id='.$this->cliente->id)->assertOk()->assertSee('80,00');
        $this->post('/carrito/pagar', ['cliente_id' => $this->cliente->id, 'metodo_pago' => 'tarjeta', 'total_centimos' => 1])
            ->assertRedirect('/pedidos/'.Pedido::firstOrFail()->id.'?cliente_id='.$this->cliente->id)->assertSessionMissing('ok');
        $pedido = Pedido::firstOrFail();
        $this->assertSame(8000, $pedido->total_centimos);
        $this->assertSame(EstadoPedido::EnPreparacion, $pedido->estado);
        $this->assertSame('tarjeta', $pedido->metodo_pago);
        $this->assertSame(2, $pedido->instantanea[0]['cantidad']);
        $this->assertDatabaseCount('lineas_carrito', 0);
        $this->get('/carrito?cliente_id='.$this->cliente->id)->assertSee('Tu selección está vacía.');
        $this->get('/pedidos')->assertOk()->assertSee('80,00')->assertSee('Ver pedido');
    }

    /** El histórico permanece aunque cambien y se borren los productos originales. */
    public function test_pedido_conserva_nombre_e_importe_historicos(): void
    {
        $producto = $this->prepararSeleccion();
        $this->post('/carrito/pagar', ['cliente_id' => $this->cliente->id, 'metodo_pago' => 'transferencia']);
        $pedido = Pedido::firstOrFail();
        $producto->update(['nombre' => 'Nombre cambiado', 'precio_centimos' => 9900]);
        $producto->delete();
        $this->get('/pedidos/'.$pedido->id)->assertOk()
            ->assertSee('Teclado histórico')->assertSee('80,00')
            ->assertSee('Transferencia simulada')->assertDontSee('Nombre cambiado');
        $this->assertSame(8000, $pedido->fresh()->instantanea[0]['precio_centimos']);
    }

    /** No se permiten pedidos vacíos, ni desde el resumen ni desde el POST directo. */
    public function test_carrito_vacio_no_genera_pedidos(): void
    {
        $this->get('/carrito/pago?cliente_id='.$this->cliente->id)->assertRedirect('/carrito?cliente_id='.$this->cliente->id)->assertSessionHasErrors('carrito');
        $this->post('/carrito/pagar', ['cliente_id' => $this->cliente->id, 'metodo_pago' => 'tarjeta'])
            ->assertRedirect('/carrito?cliente_id='.$this->cliente->id)->assertSessionHasErrors('carrito');
        $this->assertDatabaseCount('pedidos', 0);
    }

    /** La opción de pago se valida antes de modificar el carrito. */
    public function test_metodo_invalido_conserva_la_seleccion(): void
    {
        $this->prepararSeleccion();
        $this->from('/carrito/pago')->post('/carrito/pagar', ['cliente_id' => $this->cliente->id, 'metodo_pago' => 'inventado'])
            ->assertRedirect('/carrito/pago')->assertSessionHasErrors('metodo_pago');
        $this->post('/carrito/pagar', ['cliente_id' => $this->cliente->id])->assertSessionHasErrors('metodo_pago');
        $this->assertDatabaseCount('pedidos', 0);
        $this->assertDatabaseCount('lineas_carrito', 1);
    }

    /** Repetir la confirmación después del primer pago no duplica la compra. */
    public function test_segunda_confirmacion_no_duplica_el_pedido(): void
    {
        $this->prepararSeleccion();
        $this->post('/carrito/pagar', ['cliente_id' => $this->cliente->id, 'metodo_pago' => 'tarjeta'])->assertRedirect();
        $this->post('/carrito/pagar', ['cliente_id' => $this->cliente->id, 'metodo_pago' => 'tarjeta'])
            ->assertRedirect('/carrito?cliente_id='.$this->cliente->id)->assertSessionHasErrors('carrito');
        $this->assertDatabaseCount('pedidos', 1);
    }

    /** Si falla el proceso tras insertar, la transacción revierte el pedido y conserva el carrito. */
    public function test_fallo_al_guardar_revierte_la_operacion(): void
    {
        $this->prepararSeleccion();
        Pedido::created(function (): void {
            throw new RuntimeException('Fallo simulado al guardar el pedido.');
        });
        $this->post('/carrito/pagar', ['cliente_id' => $this->cliente->id, 'metodo_pago' => 'tarjeta'])
            ->assertRedirect('/carrito?cliente_id='.$this->cliente->id)->assertSessionHasErrors('carrito');
        $this->assertDatabaseCount('pedidos', 0);
        $this->assertDatabaseCount('lineas_carrito', 1);
    }

    /** El histórico se ordena por id descendente y muestra cinco registros por página. */
    public function test_historico_paginado_y_binding(): void
    {
        for ($numero = 1; $numero <= 6; $numero++) {
            Pedido::create([
                'total_centimos' => 100 * $numero,
                'instantanea' => [['producto' => 'Artículo '.$numero, 'cantidad' => 1, 'precio_centimos' => 100 * $numero]],
                'metodo_pago' => 'tarjeta', 'estado' => 2,
            ]);
        }
        $ultimoId = Pedido::max('id');
        $this->get('/pedidos')->assertOk()->assertViewHas('pedidos', fn ($p) => $p->total() === 6 && count($p) === 5 && $p->first()->id === $ultimoId);
        $this->get('/pedidos?page=2')->assertOk()->assertViewHas('pedidos', fn ($p) => count($p) === 1);
        $this->get('/pedidos/999999')->assertNotFound();
    }
}
