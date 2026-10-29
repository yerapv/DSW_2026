<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $datos = $request->validate(['cliente_id' => ['nullable', 'integer', 'exists:clientes,id']], [
            'cliente_id.integer' => 'Selecciona un cliente válido.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ]);
        $cliente = isset($datos['cliente_id'])
            ? Cliente::withCount(['presupuestos', 'pedidos'])->with('carrito.lineas')->findOrFail($datos['cliente_id'])
            : null;

        return view('usuario.index', [
            'clientes' => Cliente::orderBy('nombre')->get(),
            'cliente' => $cliente,
        ]);
    }
}
