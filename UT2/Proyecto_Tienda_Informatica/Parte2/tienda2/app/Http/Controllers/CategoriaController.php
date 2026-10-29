<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    /** With() carga también los productos sin una consulta por categoría. */
    public function index(): View
    {
        return view('usuario.categorias.index', [
            'categorias' => Categoria::with('productos')->orderBy('nombre')->get(),
        ]);
    }
}
