<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factura>
 */
class FacturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Estados facturado -> F, Pagado -> P, Cancelado -> C, Pendiente->A
        $estado = $this->faker->randomElement(['F','P','C', 'A']);
        return [
            'cliente_id' => Cliente::factory(),
            'cantidad' => $this->faker->numberBetween(100, 30000),
            'estado' => $estado,
            'fecha_creacion' => $this->faker->dateTimeThisDecade,
            'fecha_pago' => $estado == 'P' ? $this->faker->dateTimeThisDecade() : NULL,
        ];
    }
}
