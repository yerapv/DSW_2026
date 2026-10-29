<?php

namespace Tests\Feature\Administracion;

use App\Enums\EstadoPedido;
use App\Enums\TipoProducto;
use App\Models\Aviso;
use App\Models\Cliente;
use App\Models\Imagen;
use App\Models\MetodoPago;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaginacionAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_todos_los_listados_admin_tienen_paginas_numeradas_sin_repetir_filas(): void
    {
        $this->seed();
        for ($i = 1; $i <= 12; $i++) {
            Aviso::create(['titulo' => 'Aviso '.$i, 'contenido' => 'Contenido']);
            Imagen::create(['nombre' => 'Foto '.$i, 'autor' => 'Ana', 'ruta_imagen' => 'escaparate/prueba.jpg']);
            Cliente::create(['nombre' => 'Cliente '.$i, 'email' => 'cliente'.$i.'@example.test']);
            MetodoPago::create(['nombre' => 'Método '.$i, 'codigo' => 'metodo'.$i, 'activo' => true]);
            Pedido::create(['estado' => EstadoPedido::EnPreparacion, 'total_centimos' => 100, 'metodo_pago' => 'tarjeta', 'instantanea' => []]);
        }
        foreach (['productos' => 'productos', 'avisos' => 'avisos', 'imagenes' => 'imagenes',
            'clientes' => 'clientes', 'metodos-pago' => 'metodos', 'pedidos' => 'pedidos', 'pedidos/historico' => 'pedidos'] as $ruta => $variable) {
            $url = '/admin/'.$ruta;
            $primera = $this->get($url)->assertOk()->assertSee('aria-current="page"', false)
                ->assertSee('page=2', false)->assertSee('page=3', false);
            $segunda = $this->get($url.'?page=2')->assertOk()
                ->assertSee('aria-current="page"><span class="page-link">2</span>', false);
            $this->assertCount(5, $primera->viewData($variable));
            $this->assertCount(5, $segunda->viewData($variable));
            $this->assertEmpty(array_intersect($primera->viewData($variable)->pluck('id')->all(), $segunda->viewData($variable)->pluck('id')->all()));
        }
    }

    public function test_tipos_y_estados_se_almacenan_como_enteros_y_los_filtros_conservan_el_codigo(): void
    {
        $this->seed();
        $producto = Producto::firstOrFail();
        $this->assertSame(TipoProducto::Portatil, $producto->tipo);
        $this->assertSame(1, DB::table('productos')->where('id', $producto->id)->value('tipo'));
        $cliente = Cliente::firstOrFail();
        for ($i = 0; $i < 6; $i++) {
            $pedido = Pedido::create(['cliente_id' => $cliente->id, 'estado' => EstadoPedido::EnPreparacion,
                'total_centimos' => 100, 'metodo_pago' => 'tarjeta', 'instantanea' => []]);
        }
        $this->assertSame(2, DB::table('pedidos')->where('id', $pedido->id)->value('estado'));
        $this->assertSame(EstadoPedido::EnPreparacion, $pedido->fresh()->estado);
        $this->get('/admin/pedidos?estado=2&cliente_id='.$cliente->id)->assertOk()
            ->assertSee('estado=2', false)->assertSee('cliente_id='.$cliente->id, false)->assertSee('page=2', false)
            ->assertSee('value="2" selected', false);
        $this->get('/admin/productos?tipo=2')->assertOk()->assertSee('value="2" selected', false);
        $this->get('/admin/productos?tipo=periferico')->assertSessionHasErrors('tipo');
        $this->get('/admin/pedidos?estado=preparando')->assertSessionHasErrors('estado');
    }
}
