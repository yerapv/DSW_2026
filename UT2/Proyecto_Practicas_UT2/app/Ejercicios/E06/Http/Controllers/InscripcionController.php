<?php

namespace App\Ejercicios\E06\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InscripcionController extends Controller
{
    public function create(): View
    {
        return view('ejercicios.e06.inscripcion.create');
    }

    public function store(Request $request): View
    {
        return view('ejercicios.e06.inscripcion.resultado', [
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            'edad' => $request->input('edad'),
            'nivel' => $request->input('nivel'),
        ]);
    }
}
