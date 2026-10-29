<?php

namespace Tests\Unit\Montaje;

use App\Services\ServicioPrecioMontaje;
use InvalidArgumentException;
use Tests\TestCase;

class ServicioPrecioMontajeTest extends TestCase
{
    /** Dos montajes básicos a 25 € deben dar 50 €, expresados como entero. */
    public function test_montaje_basico(): void
    {
        $this->assertSame(5000, (new ServicioPrecioMontaje())->calcular(2, false, false));
    }

    /** La instalación incrementa cada equipo, no solo el total del encargo. */
    public function test_instalacion_por_equipo(): void
    {
        $this->assertSame(7000, (new ServicioPrecioMontaje())->calcular(2, true, false));
    }

    /** La urgencia añade 15 € una sola vez. */
    public function test_urgencia_con_instalacion(): void
    {
        $this->assertSame(8500, (new ServicioPrecioMontaje())->calcular(2, true, true));
    }

    /** El servicio también se protege cuando se utiliza sin formulario. */
    public function test_rechaza_cero_equipos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new ServicioPrecioMontaje())->calcular(0, false, false);
    }
}
