<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Sus rutas y formularios pertenecen al área /admin.
class AvisoController extends Controller
{
    /** Latest() ordena por fecha descendente; get() ejecuta la consulta. */
    public function index(): View
    {
        return view('admin.avisos.index', ['avisos' => Aviso::latest()->get()]);
    }

    /** No creamos el registro hasta que el usuario envíe el formulario. */
    public function create(): View
    {
        return view('admin.avisos.create');
    }

    /** La petición validada pasa al modelo y el modelo realiza el INSERT. */
    public function store(Request $request): RedirectResponse
    {
        Aviso::create($this->validar($request));

        return redirect()->route('admin.avisos.index');
    }

    public function show(Aviso $aviso): View
    {
        return view('admin.avisos.show', compact('aviso'));
    }

    public function edit(Aviso $aviso): View
    {
        return view('admin.avisos.edit', compact('aviso'));
    }

    public function update(Request $request, Aviso $aviso): RedirectResponse
    {
        $aviso->update($this->validar($request));

        return redirect()->route('admin.avisos.index');
    }

    public function destroy(Aviso $aviso): RedirectResponse
    {
        $aviso->delete();

        return redirect()->route('admin.avisos.index');
    }

    /** Alta y edición comparten las mismas reglas y mensajes. */
    private function validar(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:120'],
            'contenido' => ['required', 'string'],
        ], [
            'titulo.required' => 'Escribe el título del aviso que quieres publicar.',
            'titulo.string' => 'El título del aviso debe ser un texto.',
            'titulo.max' => 'El título del aviso no puede superar :max caracteres.',
            'contenido.required' => 'Escribe el contenido del aviso antes de publicarlo.',
            'contenido.string' => 'El contenido del aviso debe ser un texto.',
        ]);
    }
}
