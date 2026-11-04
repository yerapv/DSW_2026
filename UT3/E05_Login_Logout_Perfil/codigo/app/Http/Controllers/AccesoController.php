<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccesoController extends Controller
{
    public function create(): View
    {
        return view('auth.acceso');
    }
    public function store(Request $r): RedirectResponse
    {
        // Validar los datos del formulario
        $credenciales = $r->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);

        // Intentar autenticar al usuario con las credenciales proporcionadas
        if (!Auth::attempt($credenciales, $r->boolean('remember'))) {
            // Si la autenticación falla, redirigir de vuelta con un mensaje de error
            return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
        }
        // Regenerar la sesión para evitar ataques de fijación de sesión
        $r->session()->regenerate();

        // intended() redirige al usuario a la URL que intentaba acceder antes de iniciar sesión, o a /perfil si no hay una URL anterior
        return redirect()->intended('/perfil');
    }
    public function destroy(Request $r): RedirectResponse
    {
        // Auth::logout() cierra la sesión del usuario actual
        Auth::logout();
        // Invalidar la sesión actual y regenerar el token CSRF para mayor seguridad
        $r->session()->invalidate();
        // Regenerar el token CSRF para evitar ataques de falsificación de solicitudes entre sitios (CSRF)
        $r->session()->regenerateToken();
        return redirect('/acceso');
    }
}
