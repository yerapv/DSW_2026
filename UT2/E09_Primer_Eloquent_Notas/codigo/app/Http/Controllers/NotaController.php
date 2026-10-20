<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotaController extends Controller
{
    public function index(): View
    {
        return view('notas.index', ['notas' => Nota::latest()->get()]);
    }
    public function create(): View
    {
        return view('notas.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate(['titulo' => ['required', 'max:120'], 'contenido' => ['required']], [
            'titulo.required' => 'Escribe el título de la nota.',
            'contenido.required' => 'Escribe el contenido de la nota.',
        ]);
        Nota::create($datos);
        return redirect('/notas')->with('ok', 'Nota creada.');
    }
}
