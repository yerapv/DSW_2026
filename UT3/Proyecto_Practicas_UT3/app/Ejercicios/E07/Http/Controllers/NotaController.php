<?php

namespace App\Ejercicios\E07\Http\Controllers;

use App\Ejercicios\E07\Models\Nota;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NotaController extends Controller
{
    public function index(Request $r): View
    {
        // Se obtienen las notas del usuario autenticado, ordenadas por fecha de creación descendente, y se pasan a la vista 'notas.index'.
        return view('ejercicios.e07.notas.index', ['notas' => $r->user()->notas()->latest()->get()]);
    }

    public function store(Request $r): RedirectResponse
    {
        // Se validan los datos de la solicitud, asegurando que 'titulo' y 'contenido' sean cadenas de texto y que 'titulo' no exceda los 120 caracteres.
        $d = $r->validate(['titulo' => 'required|string|max:120', 'contenido' => 'required|string']);
        // Se crea una nueva nota asociada al usuario autenticado con los datos validados y se redirige de vuelta a la página anterior.
        $r->user()->notas()->create($d);

        return back();
    }

    public function show(Nota $nota): View
    {
        // Gate::authorize('view', $nota) es una forma de verificar si el usuario tiene permiso para realizar la acción 'view' en el modelo Nota.
        Gate::authorize('view', $nota);

        return view('ejercicios.e07.notas.show', compact('nota'));
    }

    public function update(Request $r, Nota $nota): RedirectResponse
    {
        // Se verifica que el usuario autenticado tenga permiso para actualizar la nota específica. Si no tiene permiso, se lanza una excepción de autorización.
        Gate::authorize('update', $nota);
        // Se validan los datos de la solicitud, asegurando que 'titulo' y 'contenido' sean cadenas de texto y que 'titulo' no exceda los 120 caracteres.
        // Luego se actualiza la nota con los datos validados y se redirige de vuelta a la página anterior.
        $nota->update($r->validate(['titulo' => 'required|string|max:120', 'contenido' => 'required|string']));

        return back();
    }

    public function destroy(Nota $nota): RedirectResponse
    {
        // Se verifica que el usuario autenticado tenga permiso para eliminar la nota específica. Si no tiene permiso, se lanza una excepción de autorización.
        Gate::authorize('delete', $nota);
        $nota->delete();

        return redirect('/e07/notas');
    }
}
