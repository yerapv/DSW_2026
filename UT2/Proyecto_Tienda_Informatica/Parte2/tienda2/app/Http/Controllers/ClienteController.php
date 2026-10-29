<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(): View
    {
        return view('admin.clientes.index', ['clientes' => Cliente::orderBy('nombre')->paginate(5)]);
    }

    public function create(): View
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Cliente::create($this->validar($request));

        return redirect()->route('admin.clientes.index');
    }

    public function show(Cliente $cliente): View
    {
        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($this->validar($request));

        return redirect()->route('admin.clientes.index');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('admin.clientes.index');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:150'],
            'tipo' => ['nullable', Rule::in(config('tienda.tipos_cliente'))],
            'edad' => ['nullable', 'integer', 'between:0,120'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.required' => 'Indica el nombre del cliente.',
            'nombre.string' => 'El nombre del cliente debe ser un texto.',
            'nombre.max' => 'El nombre del cliente no puede superar 80 caracteres.',
            'email.required' => 'Indica el correo electrónico del cliente.',
            'email.email' => 'Introduce un correo electrónico válido para el cliente.',
            'email.max' => 'El correo del cliente no puede superar 150 caracteres.',
            'tipo.in' => 'Selecciona un tipo de cliente válido.',
            'edad.integer' => 'La edad debe ser un número entero.',
            'edad.between' => 'La edad debe estar entre 0 y 120 años.',
            'direccion.string' => 'La dirección del cliente debe ser un texto.',
            'direccion.max' => 'La dirección del cliente no puede superar 255 caracteres.',
        ]);
    }
}
