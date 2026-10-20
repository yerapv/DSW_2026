<?php

namespace App\Ejercicios\E15\Http\Controllers;

use App\Ejercicios\E15\Services\ServicioPrecioImpresion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImprentaController extends Controller
{
    public function __construct(private readonly ServicioPrecioImpresion $precios) {}

    public function create(): View
    {
        return view('ejercicios.e15.imprenta.form');
    }

    public function calculate(Request $r): View
    {
        $d = $r->validate(['copias' => ['required', 'integer', 'min:1'], 'color' => ['sometimes', 'boolean'], 'encuadernado' => ['sometimes', 'boolean']], [
            'copias.required' => 'Indica el número de copias que deseas imprimir.',
        ]);
        $total = $this->precios->calcular($d['copias'], $r->boolean('color'), $r->boolean('encuadernado'));

        return view('ejercicios.e15.imprenta.resultado', ['totalCentimos' => $total]);
    }
}
