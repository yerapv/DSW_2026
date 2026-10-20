<?php

namespace App\Ejercicios\E10\Http\Controllers;

use App\Ejercicios\E10\Http\Requests\GuardarLibroRequest;
use App\Ejercicios\E10\Models\Libro;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LibroController extends Controller
{
    public function index(): View
    {
        return view('ejercicios.e10.libros.index', ['libros' => Libro::orderBy('titulo')->get()]);
    }

    public function create(): View
    {
        return view('ejercicios.e10.libros.create');
    }

    public function store(GuardarLibroRequest $r): RedirectResponse
    {
        Libro::create($r->validated());

        return redirect()->route('e10.libros.index')->with('ok', 'Libro creado.');
    }

    public function show(Libro $libro): View
    {
        return view('ejercicios.e10.libros.show', compact('libro'));
    }

    public function edit(Libro $libro): View
    {
        return view('ejercicios.e10.libros.edit', compact('libro'));
    }

    public function update(GuardarLibroRequest $r, Libro $libro): RedirectResponse
    {
        $libro->update($r->validated());

        return redirect()->route('e10.libros.index')->with('ok', 'Libro actualizado.');
    }

    public function destroy(Libro $libro): RedirectResponse
    {
        $libro->delete();

        return redirect()->route('e10.libros.index')->with('ok', 'Libro eliminado.');
    }
}
