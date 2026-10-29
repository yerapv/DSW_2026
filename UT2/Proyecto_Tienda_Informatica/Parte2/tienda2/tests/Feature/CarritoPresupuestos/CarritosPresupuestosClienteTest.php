<?php

namespace Tests\Feature\CarritoPresupuestos;

use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\LineaPresupuesto;
use App\Models\Pedido;
use App\Models\Presupuesto;
use App\Models\Producto;
use App\Services\ServicioPresupuestos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Tests\TestCase;

class CarritosPresupuestosClienteTest extends TestCase
{
    use RefreshDatabase;

    private Cliente $ana;

    private Cliente $luis;

    private Producto $producto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->ana = Cliente::where('nombre', 'Ana García')->firstOrFail();
        $this->luis = Cliente::where('nombre', 'Luis Pérez')->firstOrFail();
        $this->producto = Producto::firstOrFail();
    }

    private function añadir(Cliente $cliente, int $cantidad = 1): void
    {
        $this->post('/carrito/lineas', [
            'cliente_id' => $cliente->id, 'producto_id' => $this->producto->id, 'cantidad' => $cantidad,
        ])->assertRedirect('/carrito?cliente_id='.$cliente->id);
    }

    public function test_elegir_cliente_es_obligatorio_y_no_se_crea_carrito_global(): void
    {
        $this->get('/carrito')->assertOk()->assertSee('Selecciona un cliente');
        $this->assertDatabaseCount('carritos', 0);
        $this->post('/carrito/lineas', ['producto_id' => $this->producto->id, 'cantidad' => 1])
            ->assertSessionHasErrors('cliente_id');
        $this->post('/carrito/presupuesto', [])->assertSessionHasErrors('cliente_id');
        $this->assertDatabaseCount('carritos', 0);
        $this->assertDatabaseCount('presupuestos', 0);
    }

    public function test_clientes_distintos_tienen_carritos_separados(): void
    {
        $this->añadir($this->ana, 2);
        $this->añadir($this->ana, 3);
        $this->añadir($this->luis, 1);
        $this->assertDatabaseCount('carritos', 2);
        $this->assertCount(1, $this->ana->carrito->lineas);
        $this->assertSame(5, $this->ana->carrito->lineas->first()->cantidad);
        $this->assertSame($this->producto->precio_centimos * 5, $this->ana->carrito->lineas->first()->precio_centimos);
        $this->assertCount(1, $this->luis->carrito->lineas);
        $this->get('/carrito?cliente_id='.$this->ana->id)->assertOk()
            ->assertViewHas('carrito', fn ($carrito) => $carrito->cliente->id === $this->ana->id && $carrito->lineas->count() === 1);
        $this->get('/carrito?cliente_id='.$this->luis->id)->assertOk()
            ->assertViewHas('carrito', fn ($carrito) => $carrito->cliente->id === $this->luis->id && $carrito->lineas->count() === 1);
    }

    public function test_presupuesto_relaciona_cliente_productos_y_cantidades(): void
    {
        $this->añadir($this->ana, 2);
        $this->añadir($this->luis, 9);
        $this->post('/carrito/presupuesto', ['cliente_id' => $this->ana->id])->assertRedirect();
        $presupuesto = Presupuesto::firstOrFail();
        $this->assertSame($this->ana->id, $presupuesto->cliente->id);
        $this->assertSame($this->ana->nombre, $presupuesto->datos_cliente['nombre']);
        $this->assertCount(1, $presupuesto->lineas);
        $this->assertSame(2, $presupuesto->lineas->first()->cantidad);
        $this->assertSame($this->producto->id, $presupuesto->lineas->first()->producto->id);
        $this->assertTrue($presupuesto->productos->contains($this->producto));
        $this->assertTrue($this->producto->presupuestos->contains($presupuesto));
        $this->assertTrue($this->ana->presupuestos->contains($presupuesto));
        $this->assertSame($this->producto->precio_centimos * 2, $presupuesto->total_centimos);
        $this->assertDatabaseCount('lineas_carrito', 2);
        $this->get('/carrito?cliente_id='.$this->luis->id)->assertViewHas('presupuestos', fn ($p) => $p->total() === 0);
    }

    public function test_catalogo_y_ficha_conservan_cliente_en_la_compra(): void
    {
        $this->get('/productos?cliente_id='.$this->ana->id)->assertOk()
            ->assertSee('name="cliente_id" value="'.$this->ana->id.'"', false)
            ->assertSee('cliente_id='.$this->ana->id, false);
        $this->get('/productos/'.$this->producto->id.'?cliente_id='.$this->ana->id)->assertOk()
            ->assertSee('name="cliente_id" value="'.$this->ana->id.'"', false);
    }

    public function test_presupuesto_conserva_nombre_y_precio_aunque_se_borre_el_producto(): void
    {
        $this->añadir($this->ana, 2);
        $presupuesto = app(ServicioPresupuestos::class)->generar(Carrito::paraCliente($this->ana));
        $nombre = $this->producto->nombre;
        $importe = $presupuesto->total_centimos;
        $this->producto->update(['nombre' => 'Producto renombrado', 'precio_centimos' => 1]);
        $this->ana->update(['nombre' => 'Cliente renombrado']);
        $this->get('/carrito/presupuestos/'.$presupuesto->id)->assertOk()
            ->assertSee($nombre)->assertSee('Ana García')->assertDontSee('Cliente renombrado')->assertDontSee('Producto renombrado');
        $this->ana->carrito->lineas()->delete();
        $this->producto->delete();
        $linea = $presupuesto->lineas()->firstOrFail();
        $this->assertNull($linea->producto_id);
        $this->assertSame($nombre, $linea->producto_nombre);
        $this->assertSame($importe, $linea->precio_centimos);
        $this->get('/carrito/presupuestos/'.$presupuesto->id)->assertOk()->assertSee($nombre);
    }

    public function test_pagar_un_cliente_conserva_el_carrito_de_otro(): void
    {
        $this->añadir($this->ana, 2);
        $this->añadir($this->luis, 3);
        $this->post('/carrito/pagar', ['cliente_id' => $this->ana->id, 'metodo_pago' => 'tarjeta'])->assertRedirect();
        $this->assertSame($this->ana->id, Pedido::firstOrFail()->cliente_id);
        $this->assertCount(0, $this->ana->carrito->lineas);
        $this->assertCount(1, $this->luis->carrito->lineas);
        $this->assertSame(3, $this->luis->carrito->lineas->first()->cantidad);
    }

    public function test_repetir_producto_suma_unidades_e_importes_en_una_linea(): void
    {
        $original = $this->producto->precio_centimos;
        $this->añadir($this->ana, 2);
        $this->producto->update(['precio_centimos' => 100]);
        $this->añadir($this->ana, 3);
        $presupuesto = app(ServicioPresupuestos::class)->generar(Carrito::paraCliente($this->ana));
        $this->assertCount(1, $presupuesto->lineas);
        $this->assertSame(5, $presupuesto->lineas[0]->cantidad);
        $this->assertSame($original * 2 + 300, $presupuesto->lineas[0]->precio_centimos);
        $this->assertSame($original * 2 + 300, $presupuesto->total_centimos);
    }

    public function test_fallo_en_una_linea_revierte_todo_el_presupuesto(): void
    {
        $this->añadir($this->ana);
        LineaPresupuesto::created(function (): void {
            throw new RuntimeException('Fallo al guardar la línea.');
        });
        try {
            app(ServicioPresupuestos::class)->generar(Carrito::paraCliente($this->ana));
            $this->fail('Se esperaba un error de escritura.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Fallo al guardar la línea.', $exception->getMessage());
        }
        $this->assertDatabaseCount('presupuestos', 0);
        $this->assertDatabaseCount('lineas_presupuesto', 0);
        $this->assertDatabaseCount('lineas_carrito', 1);
    }

    public function test_borrar_cliente_conserva_sus_presupuestos_historicos(): void
    {
        $this->añadir($this->ana);
        $presupuesto = app(ServicioPresupuestos::class)->generar(Carrito::paraCliente($this->ana));
        $this->delete('/admin/clientes/'.$this->ana->id)->assertRedirect('/admin/clientes');
        $this->assertNull($presupuesto->fresh()->cliente_id);
        $this->assertSame('Ana García', $presupuesto->fresh()->nombreCliente());
        $this->get('/carrito/presupuestos/'.$presupuesto->id)->assertOk()->assertSee('Ana García');
    }

    public function test_escaparate_utiliza_el_nombre_nuevo_de_tabla(): void
    {
        $this->assertTrue(Schema::hasTable('escaparate_imagenes'));
        $this->assertFalse(Schema::hasTable('imagenes'));
    }
}
