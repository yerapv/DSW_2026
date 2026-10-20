<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarLibroRequest;
use App\Models\Libro;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LibroController extends Controller
{
    public function index(): View
    {
        return view('libros.index', ['libros' => Libro::orderBy('titulo')->get()]);
    }
    public function create(): View
    {
        return view('libros.create');
    }
    public function store(GuardarLibroRequest $r): RedirectResponse
    {
        Libro::create($r->validated());
        return redirect()->route('libros.index')->with('ok', 'Libro creado.');
    }
    public function show(Libro $libro): View
    {
        return view('libros.show', compact('libro'));
    }
    public function edit(Libro $libro): View
    {
        return view('libros.edit', compact('libro'));
    }
    public function update(GuardarLibroRequest $r, Libro $libro): RedirectResponse
    {
        $libro->update($r->validated());
        return redirect()->route('libros.index')->with('ok', 'Libro actualizado.');
    }
    public function destroy(Libro $libro): RedirectResponse
    {
        $libro->delete();
        return redirect()->route('libros.index')->with('ok', 'Libro eliminado.');
    }
}
