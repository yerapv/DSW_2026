<?php

namespace Tests\Unit\Transversales;

use App\Dominio\Envios\CalculadoraEnvio;
use App\Services\ServicioPrecioMontaje;
use App\Services\ValidadorSolicitudFinanciacion;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CoherenciaServiciosTest extends TestCase
{
    public static function pesosInvalidos(): array
    {
        return [[-1.0], [INF], [NAN]];
    }

    #[DataProvider('pesosInvalidos')]
    public function test_envios_rechaza_pesos_imposibles(float $peso): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new CalculadoraEnvio())->calcular($peso, false);
    }

    public function test_montaje_rechaza_cantidades_excesivas_y_acepta_limites(): void
    {
        $servicio = new ServicioPrecioMontaje();
        $this->assertSame(2500, $servicio->calcular(1, false, false));
        $this->assertSame(250000, $servicio->calcular(100, false, false));
        $this->expectException(InvalidArgumentException::class);
        $servicio->calcular(101, false, false);
    }

    public function test_financiacion_tambien_rechaza_incoherencias_sin_peticiones_http(): void
    {
        $base = ['edad' => 20, 'importe' => 1000, 'antiguedad_meses' => 12, 'documentacion_completa' => true];
        foreach (['edad' => 300, 'importe' => -1, 'antiguedad_meses' => 1000] as $campo => $valor) {
            $resultado = (new ValidadorSolicitudFinanciacion())->validar(array_replace($base, [$campo => $valor]));
            $this->assertFalse($resultado['valid']);
            $this->assertNotEmpty($resultado['errors']);
        }
    }
}
