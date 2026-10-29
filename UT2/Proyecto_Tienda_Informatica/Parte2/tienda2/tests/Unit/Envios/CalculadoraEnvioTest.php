<?php

namespace Tests\Unit\Envios;

use App\Dominio\Envios\CalculadoraEnvio;
use PHPUnit\Framework\TestCase;

class CalculadoraEnvioTest extends TestCase
{
    /** El mismo paquete permite contrastar la base y el suplemento de urgencia. */
    public function test_tarifas_del_ejemplo_de_clase(): void
    {
        $calculadora = new CalculadoraEnvio();
        $this->assertSame(5.0, $calculadora->calcular(2.5, false));
        $this->assertSame(10.0, $calculadora->calcular(2.5, true));
    }
}
