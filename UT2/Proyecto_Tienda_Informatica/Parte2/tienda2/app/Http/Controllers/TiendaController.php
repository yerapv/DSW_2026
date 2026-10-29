<?php

namespace App\Http\Controllers;

use App\Enums\TipoProducto;
use App\Models\Aviso;
use App\Models\Cliente;
use App\Models\Imagen;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

// La tienda presenta los datos; el CRUD se mantiene en ProductoController para admin.
// Se usan las mismas consultas Eloquent que en los ejercicios, sin lógica de roles.
class TiendaController extends Controller
{
    /** Catálogo público con filtros, paginación y eager loading. */
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'tipo' => ['nullable', Rule::enum(TipoProducto::class)],
        ], [
            'q.string' => 'La búsqueda debe ser un texto.',
            'q.max' => 'La búsqueda no puede superar 150 caracteres.',
            'tipo.enum' => 'Selecciona un tipo de producto válido.',
        ]);
        $productos = Producto::query()->with('categoria')
            ->when($request->filled('q'), fn ($q) => $q->where('nombre', 'like', '%'.$request->query('q').'%'))
            ->when($filtros['tipo'] ?? null, fn ($q, $tipo) => $q->where('tipo', $tipo))
            ->orderBy('nombre')->paginate(5)->withQueryString();

        return view('usuario.productos.index', $this->clientes($request) + compact('productos'));
    }

    /** Binding y casts para consultar una ficha desde la tienda. */
    public function show(Request $request, Producto $producto): View
    {
        $producto->load('categoria');

        return view('usuario.productos.show', $this->clientes($request) + compact('producto'));
    }

    /** El usuario puede leer los avisos que se publican desde admin. */
    public function avisos(): View
    {
        return view('usuario.avisos.index', ['avisos' => Aviso::latest()->get()]);
    }

    /** La galería pública solo muestra las fotografías del escaparate. */
    public function imagenes(): View
    {
        return view('usuario.imagenes.index', ['imagenes' => Imagen::latest()->get()]);
    }

    private function clientes(Request $request): array
    {
        $datos = $request->validate(['cliente_id' => ['nullable', 'integer', 'exists:clientes,id']], [
            'cliente_id.integer' => 'Selecciona un cliente válido para la compra.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ]);

        return [
            'clientes' => Cliente::orderBy('nombre')->get(),
            'cliente' => isset($datos['cliente_id']) ? Cliente::findOrFail($datos['cliente_id']) : null,
        ];
    }
}
