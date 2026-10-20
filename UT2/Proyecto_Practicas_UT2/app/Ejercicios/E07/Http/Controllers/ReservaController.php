<?php

namespace App\Ejercicios\E07\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservaController extends Controller
{
    public function create(): View
    {
        return view('ejercicios.e07.reserva.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email'],
            'personas' => ['required', 'integer', 'between:1,8'],
            'fecha' => ['required', 'date'],
        ], [
            'nombre.required' => 'Indica el nombre de la persona que realiza la reserva.',
            'email.required' => 'Indica un correo electrónico para la reserva.',
            'personas.required' => 'Indica el número de personas de la reserva.',
            'fecha.required' => 'Selecciona la fecha de la reserva.',
        ]);

        return redirect('/e07/reserva/correcta')->with('ok', 'Reserva recibida correctamente.');
    }

    public function correcta(): View
    {
        return view('ejercicios.e07.reserva.correcta');
    }
}
