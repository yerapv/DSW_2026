<?php

namespace Tests\Unit\E16;

use App\Ejercicios\E16\Services\ValidadorSolicitudResidencia;
use PHPUnit\Framework\TestCase;

class ValidadorSolicitudResidenciaTest extends TestCase
{
    private function base(): array
    {
        return ['edad' => 20, 'renta' => 20000, 'distancia_km' => 20, 'documentacion_completa' => true];
    }

    public function test_valida(): void
    {
        $this->assertTrue((new ValidadorSolicitudResidencia)->validar($this->base())['valid']);
    }

    public function test_menor(): void
    {
        $s = $this->base();
        $s['edad'] = 17;
        $this->assertFalse((new ValidadorSolicitudResidencia)->validar($s)['valid']);
    }

    public function test_renta_alta(): void
    {
        $s = $this->base();
        $s['renta'] = 50000;
        $this->assertFalse((new ValidadorSolicitudResidencia)->validar($s)['valid']);
    }

    public function test_warning_distancia(): void
    {
        $s = $this->base();
        $s['distancia_km'] = 5;
        $this->assertNotEmpty((new ValidadorSolicitudResidencia)->validar($s)['warnings']);
    }
}
