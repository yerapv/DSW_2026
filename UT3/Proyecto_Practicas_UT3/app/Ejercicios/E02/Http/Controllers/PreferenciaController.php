<?php

namespace App\Ejercicios\E02\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PreferenciaController extends Controller
{
    public function show(Request $request): View
    {
        // Recupera el valor de la cookie 'tema', si no existe, se establece el valor predeterminado como 'claro'
        return view('ejercicios.e02.lectura', ['tema' => $request->cookie('tema', 'claro')]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Valida que el valor de 'tema' sea obligatorio y esté dentro de las opciones permitidas ('claro' o 'oscuro')
        $data = $request->validate(['tema' => ['required', 'in:claro,oscuro']]);

        // Crea una cookie llamada 'tema' con el valor proporcionado y una duración de 30 días (60 minutos * 24 horas * 30 días)
        return redirect('/e02/lectura')->withCookie(cookie('tema', $data['tema'], 60 * 24 * 30));
    }

    public function destroy(): RedirectResponse
    {
        // Elimina la cookie 'tema' estableciendo su valor como nulo y su tiempo de expiración en el pasado
        return redirect('/e02/lectura')->withoutCookie('tema');
    }
}
