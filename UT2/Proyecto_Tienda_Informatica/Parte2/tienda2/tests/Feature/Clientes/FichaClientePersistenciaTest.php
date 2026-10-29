<?php

namespace Tests\Feature\Clientes;

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FichaClientePersistenciaTest extends TestCase
{
    use RefreshDatabase;

    private function datos(): array
    {
        return ['nombre' => 'Ana de prueba', 'email' => 'ana@example.test', 'tipo' => 'particular',
            'edad' => 28, 'direccion' => 'Calle Mayor, 10'];
    }

    public function test_ficha_crea_cliente_y_se_consulta_desde_la_bd(): void
    {
        $datos = $this->datos();
        $this->post('/ficha-cliente', $datos + ['id' => 999, 'campo_extra' => 'ignorar'])->assertRedirect();
        $cliente = Cliente::firstOrFail();
        $this->assertDatabaseHas('clientes', $datos);
        $this->assertSame(28, $cliente->edad);
        $this->get('/ficha-cliente/'.$cliente->id)->assertOk()->assertSee('Ficha guardada')
            ->assertSee('Calle Mayor, 10')->assertSee('28')->assertSee('Particular');
        $this->get('/usuario?cliente_id='.$cliente->id)->assertOk()->assertSee('Ana de prueba');
        $this->get('/admin/clientes/'.$cliente->id)->assertOk()->assertSee('Calle Mayor, 10')->assertSee('28');
    }

    public function test_ficha_actualiza_cliente_seleccionado_sin_duplicarlo(): void
    {
        $cliente = Cliente::create($this->datos());
        $this->get('/ficha-cliente?cliente_id='.$cliente->id)->assertOk()->assertSee('value="28"', false)
            ->assertSee('value="particular" selected', false);
        $datos = array_replace($this->datos(), ['nombre' => 'Ana actualizada', 'edad' => 30, 'tipo' => 'empresa']);
        $this->post('/ficha-cliente?cliente_id='.$cliente->id, $datos)
            ->assertRedirect('/ficha-cliente/'.$cliente->id.'?cliente_id='.$cliente->id);
        $this->assertDatabaseCount('clientes', 1);
        $this->assertDatabaseHas('clientes', $datos + ['id' => $cliente->id]);
        $this->get('/ficha-cliente/'.$cliente->id)->assertOk()->assertSee('Ana actualizada')->assertSee('Empresa');
    }

    public function test_datos_invalidos_no_crean_ni_modifican_clientes(): void
    {
        $datos = $this->datos();
        $cliente = Cliente::create($datos);
        $this->post('/ficha-cliente?cliente_id='.$cliente->id, array_replace($datos, ['edad' => -1, 'email' => 'incorrecto']))
            ->assertSessionHasErrors(['edad', 'email']);
        $this->assertDatabaseHas('clientes', $datos + ['id' => $cliente->id]);
        $this->post('/ficha-cliente?cliente_id=999999', $datos)->assertSessionHasErrors('cliente_id');
        $this->assertDatabaseCount('clientes', 1);
    }
}
