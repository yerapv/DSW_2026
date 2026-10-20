<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use App\Models\Libro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LibroEditorialController extends Controller
{
    public function create(): View
    {
        return view('editoriales.libro-create', ['editoriales' => Editorial::orderBy('nombre')->get()]);
    }
    public function store(Request $r): RedirectResponse
    {
        $d = $r->validate(['titulo' => ['required', 'max:150'], 'editorial_id' => ['required', 'exists:editoriales,id']], [
            'titulo.required' => 'Indica el título del libro.',
            'editorial_id.required' => 'Selecciona la editorial del libro.',
        ]);
        Libro::create($d);
        return redirect('/editoriales');
    }
}
