<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class InscripcionController extends Controller
{
    public function create(): View
    {
        return view('inscripcion.create');
    }
    public function store(Request $request): View
    {
        return view('inscripcion.resultado', [
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            'edad' => $request->input('edad'),
            'nivel' => $request->input('nivel'),
        ]);
    }
}
