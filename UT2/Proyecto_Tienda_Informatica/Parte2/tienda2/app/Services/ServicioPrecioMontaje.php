<?php

namespace App\Services;

use InvalidArgumentException;

// Equipos, instalación y urgencia sustituyen a copias, color y encuadernado.
final class ServicioPrecioMontaje
{
    /** Calcula el montaje en céntimos; no conoce formularios ni vistas. */
    public function calcular(int $equipos, bool $instalacion, bool $urgente): int
    {
        // Esta protección permite llamar al servicio también fuera de HTTP.
        if ($equipos < 1 || $equipos > 100) {
            throw new InvalidArgumentException('El número de equipos debe estar entre 1 y 100.');
        }

        // config() recupera los precios comunes definidos en config/tienda.php.
        $unidad = (int) config('tienda.montaje.equipo_centimos');
        if ($instalacion) {
            $unidad += (int) config('tienda.montaje.instalacion_extra_centimos');
        }

        // La instalación se cobra por equipo y la urgencia una vez por encargo.
        $total = $equipos * $unidad;
        if ($urgente) {
            $total += (int) config('tienda.montaje.urgente_centimos');
        }

        return $total;
    }
}
