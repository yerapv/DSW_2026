<?php

namespace App\Ejercicios\E06\Http\Middleware;

use App\Ejercicios\E06\Enums\Rol;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AsegurarGestor
{
    // handle es el método que se ejecuta cuando se aplica el middleware a una ruta o grupo de rutas.
    public function handle(Request $r, Closure $next): Response
    {
        abort_unless($r->user()?->rol === Rol::Gestor, 403, 'Solo gestores.');

        return $next($r);
    }
}
