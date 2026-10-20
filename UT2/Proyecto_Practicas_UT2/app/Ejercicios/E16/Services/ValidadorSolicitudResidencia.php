<?php

namespace App\Ejercicios\E16\Services;

final class ValidadorSolicitudResidencia
{
    public function validar(array $s): array
    {
        $errors = [];
        $warnings = [];
        if ($s['edad'] < 18) {
            $errors[] = 'Debe ser mayor de edad.';
        }
        if ($s['renta'] > 45000) {
            $errors[] = 'La renta supera el máximo permitido.';
        }
        if ($s['distancia_km'] < 10) {
            $warnings[] = 'La distancia al centro es baja; la solicitud tendrá menor prioridad.';
        }
        if (! $s['documentacion_completa']) {
            $errors[] = 'Falta documentación.';
        }

        return ['valid' => $errors === [], 'errors' => $errors, 'warnings' => $warnings];
    }
}
