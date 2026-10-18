<?php

namespace App\Dominio\Envios;

final class CalculadoraEnvio
{
    public function calcular(float $peso, bool $urgente): float
    {
        $precio = 3 + ($peso * 0.80);
        if ($urgente) {
            $precio += 5;
        }
        return round($precio, 2);
    }
}
