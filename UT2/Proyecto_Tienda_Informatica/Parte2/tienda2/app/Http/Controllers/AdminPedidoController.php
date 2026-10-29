<?php

namespace App\Http\Controllers;

use App\Enums\EstadoPedido;
use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPedidoController extends Controller
{
    public function index(Request $request): View
    {
        return $this->listado($request, 'admin.pedidos.index');
    }

    public function historico(Request $request): View
    {
        return $this->listado($request, 'admin.pedidos.historico');
    }

    public function show(Pedido $pedido): View
    {
        return view('admin.pedidos.show', compact('pedido'));
    }

    public function edit(Pedido $pedido): View
    {
        return view('admin.pedidos.edit', ['pedido' => $pedido, 'estados' => EstadoPedido::cases()]);
    }

    public function update(Request $request, Pedido $pedido): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::enum(EstadoPedido::class)],
        ], [
            'estado.required' => 'Selecciona el estado del pedido.',
            'estado.enum' => 'Selecciona un estado de pedido válido.',
        ]);
        $pedido->update(['estado' => EstadoPedido::from($datos['estado'])]);

        return redirect()->route('admin.pedidos.index');
    }

    private function opciones(): array
    {
        return [
            'clientes' => Cliente::orderBy('nombre')->get(),
            'estados' => EstadoPedido::cases(),
        ];
    }

    private function listado(Request $request, string $vista): View
    {
        $filtros = $request->validate([
            'estado' => ['nullable', Rule::enum(EstadoPedido::class)],
            'cliente_id' => ['nullable', 'integer', 'exists:clientes,id'],
        ], ['estado.enum' => 'Selecciona un estado de pedido válido.', 'cliente_id.exists' => 'Selecciona un cliente existente.']);
        $pedidos = Pedido::query()
            ->when($filtros['estado'] ?? null, fn ($q, $estado) => $q->where('estado', $estado))
            ->when($filtros['cliente_id'] ?? null, fn ($q, $cliente) => $q->where('cliente_id', $cliente))
            ->orderByDesc('id')->paginate(5)->withQueryString();

        return view($vista, $this->opciones() + compact('pedidos'));
    }
}
