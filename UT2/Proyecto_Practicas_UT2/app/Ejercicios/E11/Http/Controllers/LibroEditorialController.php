<?php

namespace App\Ejercicios\E11\Http\Controllers;

use App\Ejercicios\E11\Models\Editorial;
use App\Ejercicios\E11\Models\Libro;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LibroEditorialController extends Controller
{
    public function create(): View
    {
        return view('ejercicios.e11.editoriales.libro-create', ['editoriales' => Editorial::orderBy('nombre')->get()]);
    }

    public function store(Request $r): RedirectResponse
    {
        $d = $r->validate(['titulo' => ['required', 'max:150'], 'editorial_id' => ['required', 'exists:editoriales,id']], [
            'titulo.required' => 'Indica el título del libro.',
            'editorial_id.required' => 'Selecciona la editorial del libro.',
        ]);
        Libro::create($d);

        return redirect('/e11/editoriales');
    }
}
