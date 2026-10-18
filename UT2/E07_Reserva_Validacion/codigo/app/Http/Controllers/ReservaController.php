<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservaController extends Controller
{
    public function create(): View
    {
        return view('reserva.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email'],
            'personas' => ['required', 'integer', 'between:1,8'],
            'fecha' => ['required', 'date'],
        ]);
        return redirect('/reserva/correcta')->with('ok', 'Reserva recibida correctamente.');
    }

    public function correcta(): View
    {
        return view('reserva.correcta');
    }
}
