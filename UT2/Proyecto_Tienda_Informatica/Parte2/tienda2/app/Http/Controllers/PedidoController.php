<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\View\View;

// El histórico solo necesita consulta: index y show, como las lecturas de Eloquent.
class PedidoController extends Controller
{
    /** Los pedidos más recientes aparecen primero y se paginan de cinco en cinco. */
    public function index(Request $request): View
    {
        $datos = $request->validate(['cliente_id' => ['nullable', 'integer', 'exists:clientes,id']], [
            'cliente_id.integer' => 'Selecciona un cliente válido.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ]);
        $cliente = isset($datos['cliente_id']) ? Cliente::findOrFail($datos['cliente_id']) : null;
        $pedidos = Pedido::query()
            ->when($cliente, fn ($consulta) => $consulta->where('cliente_id', $cliente->id))
            ->orderByDesc('id')->paginate(5)->withQueryString();

        return view('usuario.pedidos.index', [
            'pedidos' => $pedidos,
            'cliente' => $cliente,
            'clientes' => Cliente::orderBy('nombre')->get(),
        ]);
    }

    /** El binding resuelve el pedido y su vista lee la instantánea, no el catálogo. */
    public function show(Pedido $pedido): View
    {
        return view('usuario.pedidos.show', compact('pedido'));
    }
}
