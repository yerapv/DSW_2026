<?php

namespace App\Ejercicios\E16\Http\Controllers;

use App\Ejercicios\E16\Services\ValidadorSolicitudResidencia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SolicitudResidenciaController extends Controller
{
    public function __construct(private readonly ValidadorSolicitudResidencia $validador) {}

    public function create(): View
    {
        return view('ejercicios.e16.residencia.form');
    }

    public function store(Request $r): View
    {
        $d = $r->validate(['edad' => ['required', 'integer', 'min:0'], 'renta' => ['required', 'numeric', 'min:0'], 'distancia_km' => ['required', 'numeric', 'min:0']], [
            'edad.required' => 'Indica la edad de la persona solicitante.',
            'renta.required' => 'Indica la renta de la persona solicitante.',
            'distancia_km.required' => 'Indica la distancia al centro en kilómetros.',
        ]);
        $d['documentacion_completa'] = $r->boolean('documentacion_completa');

        return view('ejercicios.e16.residencia.resultado', ['resultado' => $this->validador->validar($d)]);
    }
}
