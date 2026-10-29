<?php

namespace App\Http\Controllers;

use App\Services\ServicioPrecioMontaje;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MontajeController extends Controller
{
    /** Laravel construye esta dependencia; ya no necesitamos usar new aquí. */
    public function __construct(private readonly ServicioPrecioMontaje $precios) {}

    /** GET presenta las opciones del cálculo sin persistencia. */
    public function create(): View
    {
        return view('usuario.montaje.form');
    }

    /** El Controller valida HTTP y delega la fórmula al Service. */
    public function calculate(Request $request): View
    {
        $datos = $request->validate([
            'equipos' => ['required', 'integer', 'between:1,100'],
            'instalacion' => ['sometimes', 'boolean'],
            'urgente' => ['sometimes', 'boolean'],
        ], [
            // Mensajes propios del formulario, independientes del idioma global.
            'equipos.required' => 'Indica cuántos equipos deseas montar.',
            'equipos.integer' => 'El número de equipos para el montaje debe ser un entero.',
            'equipos.between' => 'Puedes solicitar el montaje de entre :min y :max equipos.',
            'instalacion.boolean' => 'La instalación del sistema debe estar activada o desactivada.',
            'urgente.boolean' => 'La opción de montaje urgente debe estar activada o desactivada.',
        ]);
        $total = $this->precios->calcular(
            (int) $datos['equipos'], $request->boolean('instalacion'), $request->boolean('urgente'),
        );

        return view('usuario.montaje.resultado', ['totalCentimos' => $total]);
    }
}
