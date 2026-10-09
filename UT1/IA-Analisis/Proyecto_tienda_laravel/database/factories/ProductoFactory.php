<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->bothify('SKU-####??'),
            'nombre' => fake()->words(3, true),
            'categoria_id' => Categoria::factory(),
            'descripcion' => fake()->optional()->sentence(),
            'precio' => fake()->randomFloat(2, 1, 9999.99),
            'stock' => fake()->numberBetween(0, 500),
            'activo' => fake()->boolean(85),
        ];
    }

    public function activo(): static
    {
        return $this->state(fn (array $attributes) => ['activo' => true]);
    }

    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => ['activo' => false]);
    }
}
