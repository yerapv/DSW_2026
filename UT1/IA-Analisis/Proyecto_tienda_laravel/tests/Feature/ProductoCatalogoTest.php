<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoCatalogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogo_muestra_productos_ordenados_por_nombre(): void
    {
        $categoria = Categoria::factory()->create(['nombre' => 'Periféricos']);
        Producto::factory()->for($categoria)->create(['nombre' => 'Teclado', 'sku' => 'PER-002']);
        Producto::factory()->for($categoria)->create(['nombre' => 'Ratón', 'sku' => 'PER-001']);

        $response = $this->get('/productos');

        $response->assertOk()
            ->assertSeeInOrder(['Ratón', 'Teclado'])
            ->assertSee('PER-001')
            ->assertSee('PER-002')
            ->assertSee('Periféricos');
    }

    public function test_busqueda_encuentra_productos_por_nombre_o_sku(): void
    {
        $categoria = Categoria::factory()->create();
        Producto::factory()->for($categoria)->create(['nombre' => 'Portátil para diseño', 'sku' => 'PORT-001']);
        Producto::factory()->for($categoria)->create(['nombre' => 'Monitor profesional', 'sku' => 'PORT-002']);

        $this->get('/productos?q=diseño')
            ->assertOk()
            ->assertSee('Portátil para diseño')
            ->assertDontSee('Monitor profesional');

        $this->get('/productos?q=PORT-002')
            ->assertOk()
            ->assertSee('Monitor profesional')
            ->assertDontSee('Portátil para diseño');
    }

    public function test_filtro_muestra_solo_productos_de_la_categoria_seleccionada(): void
    {
        $portatiles = Categoria::factory()->create(['nombre' => 'Portátiles']);
        $monitores = Categoria::factory()->create(['nombre' => 'Monitores']);
        Producto::factory()->for($portatiles)->create(['nombre' => 'Equipo portátil']);
        Producto::factory()->for($monitores)->create(['nombre' => 'Pantalla externa']);

        $this->get('/productos?categoria='.$portatiles->id)
            ->assertOk()
            ->assertSee('Equipo portátil')
            ->assertDontSee('Pantalla externa');
    }

    public function test_catalogo_indica_cuando_no_hay_resultados(): void
    {
        $this->get('/productos?q=producto-inexistente')
            ->assertOk()
            ->assertSee('No se encontraron productos');
    }

    public function test_filtro_rechaza_una_categoria_inexistente(): void
    {
        $this->get('/productos?categoria=999')
            ->assertSessionHasErrors('categoria');
    }
}
