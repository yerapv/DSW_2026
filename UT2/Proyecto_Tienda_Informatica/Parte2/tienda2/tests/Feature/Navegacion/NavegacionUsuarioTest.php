<?php

namespace Tests\Feature\Navegacion;

use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Services\ServicioPresupuestos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Comprueba los cambios comunes de presentación sin depender del historial del navegador.
class NavegacionUsuarioTest extends TestCase
{
    use RefreshDatabase;

    /** Las pantallas interiores mantienen Volver; la página principal de Usuario no lo muestra. */
    public function test_paginas_de_usuario_incluyen_boton_volver(): void
    {
        $this->seed();
        $producto = Producto::firstOrFail();
        $cliente = Cliente::firstOrFail();
        $this->post('/carrito/lineas', ['cliente_id' => $cliente->id, 'producto_id' => $producto->id, 'cantidad' => 1]);
        $presupuesto = app(ServicioPresupuestos::class)->generar(Carrito::paraCliente($cliente));
        $pedido = Pedido::create(['total_centimos' => 100, 'instantanea' => [], 'metodo_pago' => 'tarjeta', 'estado' => 2]);
        $this->get('/usuario')->assertOk()->assertDontSee('← Volver');
        foreach (['/', '/novedades', '/envios',
            '/ficha-cliente', '/avisos', '/productos',
            '/categorias', '/montaje', '/financiacion', '/imagenes', '/carrito',
            '/carrito/pago?cliente_id='.$cliente->id, '/pedidos', '/productos/'.$producto->id,
            '/carrito/presupuestos/'.$presupuesto->id, '/pedidos/'.$pedido->id] as $pagina) {
            $this->get($pagina)->assertOk()->assertSee('back-button', false)->assertSee('← Volver');
        }
        $this->get('/productos/'.$producto->id)->assertSee('href="'.route('productos.index').'"', false);
        $this->get('/pedidos/'.$pedido->id)->assertSee('href="'.route('pedidos.index').'"', false);
    }

    /** Los resultados POST también heredan el botón del layout de usuario. */
    public function test_resultados_de_formularios_incluyen_volver(): void
    {
        $this->post('/ficha-cliente', ['nombre' => 'Ana', 'email' => 'ana@example.test', 'edad' => 20, 'tipo' => 'estudiante'])
            ->assertRedirect();
        $this->get('/ficha-cliente/'.Cliente::firstOrFail()->id)->assertOk()->assertSee('← Volver');
        $this->post('/montaje', ['equipos' => 1])->assertOk()->assertSee('← Volver');
        $this->post('/financiacion', ['edad' => 20, 'importe' => 1000, 'antiguedad_meses' => 12, 'documentacion_completa' => 1])
            ->assertOk()->assertSee('← Volver');
    }

    /** El nombre nuevo se muestra en ambas áreas y las tarjetas ya no tienen numeración. */
    public function test_empresa_y_tarjetas_sin_numeros(): void
    {
        $this->assertSame('NEXIUM SOFTWARE FACTORY SL', config('tienda.nombre'));
        foreach (['/', '/usuario', '/admin'] as $pagina) {
            $this->get($pagina)->assertOk()->assertSee('NEXIUM SOFTWARE FACTORY SL')
                ->assertDontSee('NexoTech')->assertDontSee('class="number"', false);
        }
    }
}
