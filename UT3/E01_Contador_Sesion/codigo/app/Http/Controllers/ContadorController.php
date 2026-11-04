<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContadorController extends Controller
{
    public function show(Request $request): View
    {
        // Obtiene el valor de la sesión, si no existe, lo inicializa en 0
        $visitas = (int) $request->session()->get('visitas', 0) + 1;
        // Actualiza el valor de la sesión
        $request->session()->put('visitas', $visitas);
        return view('contador', compact('visitas'));
    }
    public function reset(Request $request): RedirectResponse
    {
        // Elimina la variable de sesión 'visitas'
        $request->session()->forget('visitas');
        return redirect('/contador');
    }
}
