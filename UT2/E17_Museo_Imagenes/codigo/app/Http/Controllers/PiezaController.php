<?php

namespace App\Http\Controllers;

use App\Models\Pieza;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PiezaController extends Controller
{
    public function index(): View
    {
        return view('piezas.index', ['piezas' => Pieza::latest()->get()]);
    }
    public function create(): View
    {
        return view('piezas.create');
    }
    public function store(Request $r): RedirectResponse
    {
        $d = $r->validate(['nombre' => ['required', 'max:120'], 'autor' => ['required', 'max:120'], 'imagen' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']], [
            'nombre.required' => 'Indica el nombre de la pieza.',
            'autor.required' => 'Indica el autor de la pieza.',
            'imagen.required' => 'Selecciona una imagen de la pieza.',
        ]);
        $ruta = $r->file('imagen')->store('piezas', 'public');
        Pieza::create(['nombre' => $d['nombre'], 'autor' => $d['autor'], 'ruta_imagen' => $ruta]);
        return redirect('/piezas');
    }
    public function destroy(Pieza $pieza): RedirectResponse
    {
        $pieza->delete();
        return back();
    }
}
