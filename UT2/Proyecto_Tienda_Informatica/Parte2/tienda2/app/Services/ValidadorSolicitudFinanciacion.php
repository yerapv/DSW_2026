<?php

namespace App\Services;

// Los límites son ficticios y sirven únicamente para practicar condicionales.
final class ValidadorSolicitudFinanciacion
{
    /** Separa los errores que impiden continuar de los avisos informativos. */
    public function validar(array $solicitud): array
    {
        $errors = [];
        $warnings = [];

        if ($solicitud['edad'] < 0 || $solicitud['edad'] > 120) {
            $errors[] = 'La edad debe estar entre 0 y 120 años.';
        }
        if ($solicitud['antiguedad_meses'] < 0 || $solicitud['antiguedad_meses'] > $solicitud['edad'] * 12) {
            $errors[] = 'La antigüedad laboral es incompatible con la edad del solicitante.';
        }
        if (! is_finite((float) $solicitud['importe']) || $solicitud['importe'] < 0) {
            $errors[] = 'El importe debe ser finito y no negativo.';
        }
        // Las reglas de negocio valoran datos cuya forma ya validó el Controller.
        if ($solicitud['edad'] < 18) {
            $errors[] = 'Debe ser mayor de edad.';
        }
        if ($solicitud['importe'] > 3000) {
            $errors[] = 'El importe supera el máximo de 3.000 €.';
        }
        if ($solicitud['antiguedad_meses'] < 6) {
            $warnings[] = 'La antigüedad es baja; la solicitud necesitaría revisión.';
        }
        if (! $solicitud['documentacion_completa']) {
            $errors[] = 'Falta documentación.';
        }

        // Una advertencia no invalida por sí misma la solicitud.
        return ['valid' => $errors === [], 'errors' => $errors, 'warnings' => $warnings];
    }
}
