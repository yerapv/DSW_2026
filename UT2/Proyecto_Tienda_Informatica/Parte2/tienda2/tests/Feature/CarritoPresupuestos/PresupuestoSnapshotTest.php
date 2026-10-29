<?php

namespace Tests\Feature\CarritoPresupuestos;

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\LineaCarrito;
use App\Models\Presupuesto;
use App\Models\Producto;
use App\Services\ServicioPresupuestos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PresupuestoSnapshotTest extends TestCase
{
    use RefreshDatabase;

    private Cliente $cliente;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cliente = Cliente::create(['nombre' => 'Cliente de prueba', 'email' => 'cliente@example.test']);
    }

    /** Construye un producto con todos los campos del CRUD integrado. */
    private function producto(): Producto
    {
        $categoria = Categoria::create(['nombre' => 'Periféricos']);

        return Producto::create(['nombre' => 'Teclado', 'marca' => 'Nexo', 'anio' => 2026,
            'disponible' => true, 'precio_centimos' => 4000, 'categoria_id' => $categoria->id,
            'tipo' => 2, 'caracteristicas' => ['conexion' => 'USB']]);
    }

    /** El presupuesto conserva los datos de la línea aunque cambie el catálogo. */
    public function test_presupuesto_conserva_precio_y_nombre_historicos(): void
    {
        $producto = $this->producto();
        $carrito = Carrito::paraCliente($this->cliente);
        LineaCarrito::create(['carrito_id' => $carrito->id, 'producto_id' => $producto->id, 'cantidad' => 2, 'precio_centimos' => 8000]);
        $presupuesto = app(ServicioPresupuestos::class)->generar($carrito);
        $producto->update(['nombre' => 'Teclado nuevo', 'precio_centimos' => 5500]);
        $this->assertSame(8000, $presupuesto->fresh()->total_centimos);
        $this->assertSame('Teclado', $presupuesto->fresh()->instantanea[0]['producto']);
        $this->assertSame(8000, $presupuesto->fresh()->instantanea[0]['precio_centimos']);
        $this->get('/carrito/presupuestos/'.$presupuesto->id)->assertOk()->assertSee('80,00')->assertDontSee('Teclado nuevo');
    }

    public function test_servicio_rechaza_carrito_vacio(): void
    {
        $carrito = Carrito::paraCliente($this->cliente);
        $this->expectException(RuntimeException::class);
        app(ServicioPresupuestos::class)->generar($carrito);
    }

    /** HTTP traduce la excepción a un error visible sin crear presupuestos vacíos. */
    public function test_formulario_informa_del_carrito_vacio(): void
    {
        $this->post('/carrito/presupuesto', ['cliente_id' => $this->cliente->id])->assertRedirect('/carrito?cliente_id='.$this->cliente->id)->assertSessionHasErrors('carrito');
        $this->assertDatabaseCount('presupuestos', 0);
    }

    /** El precio enviado por un navegador nunca sustituye al calculado en servidor. */
    public function test_flujo_de_seleccion_y_presupuesto(): void
    {
        $producto = $this->producto();
        $this->post('/carrito/lineas', ['cliente_id' => $this->cliente->id, 'producto_id' => $producto->id, 'cantidad' => 3, 'precio_centimos' => 1])
            ->assertRedirect('/carrito?cliente_id='.$this->cliente->id);
        $this->assertDatabaseHas('lineas_carrito', ['cantidad' => 3, 'precio_centimos' => 12000]);
        $this->post('/carrito/presupuesto', ['cliente_id' => $this->cliente->id])->assertRedirect('/carrito/presupuestos/'.Presupuesto::firstOrFail()->id.'?cliente_id='.$this->cliente->id);
        $this->assertSame(12000, Presupuesto::firstOrFail()->total_centimos);
    }

    /** El CRUD respeta la FK del carrito y responde con un mensaje en vez de error SQL. */
    public function test_producto_en_carrito_no_se_borra(): void
    {
        $producto = $this->producto();
        $this->post('/carrito/lineas', ['cliente_id' => $this->cliente->id, 'producto_id' => $producto->id, 'cantidad' => 1]);
        $this->from('/admin/productos')->delete('/admin/productos/'.$producto->id)->assertRedirect('/admin/productos')->assertSessionHasErrors('producto');
        $this->assertDatabaseHas('productos', ['id' => $producto->id]);
    }
}
