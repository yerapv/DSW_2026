<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', 'integer', 'exists:categorias,id'],
        ]);

        $productos = Producto::query()
            ->with('categoria')
            ->when($filtros['q'] ?? null, function ($query, string $termino) {
                $query->where(function ($query) use ($termino) {
                    $query->where('nombre', 'like', "%{$termino}%")
                        ->orWhere('sku', 'like', "%{$termino}%");
                });
            })
            ->when($filtros['categoria'] ?? null, fn ($query, int $categoriaId) => $query->where('categoria_id', $categoriaId))
            ->orderBy('nombre')
            ->get();

        return view('productos.index', [
            'productos' => $productos,
            'categorias' => Categoria::orderBy('nombre')->get(),
            'busqueda' => $filtros['q'] ?? '',
            'categoriaSeleccionada' => $filtros['categoria'] ?? '',
        ]);
    }
}
