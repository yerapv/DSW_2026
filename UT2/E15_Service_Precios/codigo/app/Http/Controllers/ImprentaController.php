<?php

namespace App\Http\Controllers;

use App\Services\ServicioPrecioImpresion;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImprentaController extends Controller
{
    public function __construct(private readonly ServicioPrecioImpresion $precios) {}
    public function create(): View
    {
        return view('imprenta.form');
    }
    public function calculate(Request $r): View
    {
        $d = $r->validate(['copias' => ['required', 'integer', 'min:1'], 'color' => ['sometimes', 'boolean'], 'encuadernado' => ['sometimes', 'boolean']], [
            'copias.required' => 'Indica el número de copias que deseas imprimir.',
        ]);
        $total = $this->precios->calcular($d['copias'], $r->boolean('color'), $r->boolean('encuadernado'));
        return view('imprenta.resultado', ['totalCentimos' => $total]);
    }
}
