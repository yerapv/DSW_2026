<?php

namespace App\Ejercicios\E15\Services;

use InvalidArgumentException;

final class ServicioPrecioImpresion
{
    public function calcular(int $copias, bool $color, bool $encuadernado): int
    {
        if ($copias < 1) {
            throw new InvalidArgumentException('Las copias deben ser positivas.');
        }
        $unidad = (int) config('imprenta.copia_centimos');
        if ($color) {
            $unidad += (int) config('imprenta.color_extra_centimos');
        }
        $total = $copias * $unidad;
        if ($encuadernado) {
            $total += (int) config('imprenta.encuadernado_centimos');
        }

        return $total;
    }
}
