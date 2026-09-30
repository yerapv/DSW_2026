<?php

namespace Tests\Feature;

use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriaFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_categoria_factory_crea_categoria_con_descripcion_opcional(): void
    {
        $categoria = Categoria::factory()->create([
            'nombre' => 'Accesorios de prueba',
            'descripcion' => null,
        ]);

        $this->assertModelExists($categoria);
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre' => 'Accesorios de prueba',
            'descripcion' => null,
        ]);
    }
}
