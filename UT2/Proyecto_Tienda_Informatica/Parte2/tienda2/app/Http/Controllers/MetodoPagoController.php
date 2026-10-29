<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MetodoPagoController extends Controller
{
    public function index(): View
    {
        return view('admin.metodos.index', ['metodos' => MetodoPago::orderBy('nombre')->paginate(5)]);
    }

    public function create(): View
    {
        return view('admin.metodos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        MetodoPago::create($this->validar($request));

        return redirect()->route('admin.metodos-pago.index');
    }

    public function show(MetodoPago $metodo): View
    {
        return view('admin.metodos.show', compact('metodo'));
    }

    public function edit(MetodoPago $metodo): View
    {
        return view('admin.metodos.edit', compact('metodo'));
    }

    public function update(Request $request, MetodoPago $metodo): RedirectResponse
    {
        $metodo->update($this->validar($request, $metodo));

        return redirect()->route('admin.metodos-pago.index');
    }

    public function destroy(MetodoPago $metodo): RedirectResponse
    {
        $metodo->delete();

        return redirect()->route('admin.metodos-pago.index');
    }

    private function validar(Request $request, ?MetodoPago $metodo = null): array
    {
        $request->merge(['activo' => $request->input('activo', false)]);

        return $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'codigo' => ['required', 'string', 'max:20', 'regex:/\A[a-z0-9_-]+\z/', Rule::unique('metodos_pago', 'codigo')->ignore($metodo)],
            'activo' => ['required', 'boolean'],
        ], [
            'nombre.required' => 'Indica el nombre del método de pago.',
            'nombre.string' => 'El nombre del método de pago debe ser un texto.',
            'nombre.max' => 'El nombre del método de pago no puede superar 80 caracteres.',
            'codigo.required' => 'Indica un código para el método de pago.',
            'codigo.string' => 'El código del método de pago debe ser un texto.',
            'codigo.max' => 'El código del método de pago no puede superar 20 caracteres.',
            'codigo.regex' => 'Utiliza letras minúsculas, números, guiones o guiones bajos en el código.',
            'codigo.unique' => 'Ya existe un método de pago con ese código.',
            'activo.boolean' => 'Indica si el método de pago está activo.',
        ]);
    }
}
