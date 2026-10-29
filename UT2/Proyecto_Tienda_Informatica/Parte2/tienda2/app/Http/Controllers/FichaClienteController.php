<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FichaClienteController extends Controller
{
    public function create(Request $request): View
    {
        $datos = $request->validate(['cliente_id' => ['nullable', 'integer', 'exists:clientes,id']], [
            'cliente_id.integer' => 'Selecciona un cliente válido.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ]);

        return view('usuario.fichas.create', [
            'tipos' => config('tienda.tipos_cliente'),
            'cliente' => isset($datos['cliente_id']) ? Cliente::findOrFail($datos['cliente_id']) : null,
        ]);
    }

    public function store(GuardarClienteRequest $request): RedirectResponse
    {
        $datos = $request->validated();
        $clienteId = $datos['cliente_id'] ?? null;
        unset($datos['cliente_id']);
        if ($clienteId) {
            $cliente = Cliente::findOrFail($clienteId);
            $cliente->update($datos);
        } else {
            $cliente = Cliente::create($datos);
        }

        return redirect()->route('fichas.show', ['cliente' => $cliente, 'cliente_id' => $cliente->id]);
    }

    public function show(Cliente $cliente): View
    {
        return view('usuario.fichas.show', compact('cliente'));
    }
}
