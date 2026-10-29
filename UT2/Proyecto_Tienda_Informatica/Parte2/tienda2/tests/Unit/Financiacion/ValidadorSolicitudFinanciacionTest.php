<?php

namespace Tests\Unit\Financiacion;

use App\Services\ValidadorSolicitudFinanciacion;
use PHPUnit\Framework\TestCase;

class ValidadorSolicitudFinanciacionTest extends TestCase
{
    /** Datos válidos que sirven de punto de partida para las reglas. */
    private function base(): array
    {
        return ['edad' => 20, 'importe' => 1000, 'antiguedad_meses' => 12, 'documentacion_completa' => true];
    }

    /** Una solicitud completa que respeta los límites se acepta. */
    public function test_solicitud_valida(): void
    {
        $this->assertTrue((new ValidadorSolicitudFinanciacion())->validar($this->base())['valid']);
    }

    /** Ser menor de edad genera un error bloqueante. */
    public function test_menor_de_edad(): void
    {
        $datos = $this->base();
        $datos['edad'] = 17;
        $this->assertFalse((new ValidadorSolicitudFinanciacion())->validar($datos)['valid']);
    }

    /** El importe máximo es inclusivo: 3000 pasa y 3001 falla. */
    public function test_limite_de_importe(): void
    {
        $datos = $this->base();
        $datos['importe'] = 3000;
        $this->assertTrue((new ValidadorSolicitudFinanciacion())->validar($datos)['valid']);
        $datos['importe'] = 3001;
        $this->assertFalse((new ValidadorSolicitudFinanciacion())->validar($datos)['valid']);
    }

    /** Los avisos no convierten una solicitud válida en inválida. */
    public function test_advertencia_por_antiguedad(): void
    {
        $datos = $this->base();
        $datos['antiguedad_meses'] = 2;
        $resultado = (new ValidadorSolicitudFinanciacion())->validar($datos);
        $this->assertTrue($resultado['valid']);
        $this->assertNotEmpty($resultado['warnings']);
    }

    /** La falta de documentación sí impide aceptar la solicitud. */
    public function test_documentacion_incompleta(): void
    {
        $datos = $this->base();
        $datos['documentacion_completa'] = false;
        $this->assertFalse((new ValidadorSolicitudFinanciacion())->validar($datos)['valid']);
    }
}
