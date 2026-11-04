<?php

namespace App\Ejercicios\E04\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistroController extends Controller
{
    public function create(): View
    {
        return view('ejercicios.e04.auth.registro');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validar los datos del formulario
        $data = $request->validate(['name' => ['required', 'string', 'max:100'], 'email' => ['required', 'email', 'unique:users,email'], 'password' => ['required', 'string', 'min:8', 'confirmed']]);
        // Crear un nuevo usuario y autenticación
        $user = User::create($data);
        // Autenticar al usuario recién registrado
        Auth::login($user);
        // Regenerar la sesión para evitar ataques de fijación de sesión
        $request->session()->regenerate();

        return redirect('/e04/bienvenida');
    }
}
