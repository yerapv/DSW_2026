<?php

namespace App\Dominio\Envios;

use InvalidArgumentException;

// No depende de Laravel ni accede a peticiones, vistas o base de datos.
final class CalculadoraEnvio
{
    /** Recibe el peso en kg y devuelve el coste del envío en euros. */
    public function calcular(float $peso, bool $urgente): float
    {
        if (! is_finite($peso) || $peso < 0) {
            throw new InvalidArgumentException('El peso debe ser un número finito no negativo.');
        }
        // La tarifa base es 3 € y cada kg añade 0,80 €.
        $precio = 3 + ($peso * 0.80);

        // El booleano decide si hay que sumar el suplemento de urgencia.
        if ($urgente) {
            $precio += 5;
        }

        // round limita el resultado a dos decimales para mostrarlo.
        return round($precio, 2);
    }
}
