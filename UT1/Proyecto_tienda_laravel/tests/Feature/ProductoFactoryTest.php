<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_producto_factory_crea_producto_con_categoria(): void
    {
        $producto = Producto::factory()->create([
            'sku' => 'FACT-0001',
            'nombre' => 'Producto de prueba',
        ]);

        $this->assertModelExists($producto);
        $this->assertInstanceOf(Categoria::class, $producto->categoria);
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'sku' => 'FACT-0001',
            'nombre' => 'Producto de prueba',
            'categoria_id' => $producto->categoria_id,
        ]);
    }

    public function test_factory_establece_el_estado_activo_e_inactivo(): void
    {
        $activo = Producto::factory()->activo()->create();
        $inactivo = Producto::factory()->inactivo()->create();

        $this->assertTrue($activo->activo);
        $this->assertFalse($inactivo->activo);
    }
}
