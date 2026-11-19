<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Individual o empresa
        $tipo = $this->faker->randomElement(['I', 'E']);
        // Nombre la empres o del cliente individual.
        $nombre = $tipo == 'I' ? $this->faker->name() : $this->faker->company();

        return [
            'nombre' => $nombre,
            'tipo' => $tipo,
            'email' => $this->faker->email(),
            'direccion' => $this->faker->streetAddress(),
            'ciudad' => $this->faker->city(),
            'codigo_postal' => $this->faker->postcode()
        ];
    }
}
