<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NovedadController extends Controller
{
    /** Request permite leer ?tipo=periferico desde la URL. */
    public function index(Request $request): View
    {
        $novedades = [
            ['nombre' => 'Teclado compacto', 'tipo' => 'periferico', 'precio' => 35],
            ['nombre' => 'Portátil ligero', 'tipo' => 'portatil', 'precio' => 599],
            ['nombre' => 'Memoria DDR5', 'tipo' => 'componente', 'precio' => 65],
            ['nombre' => 'Ratón vertical', 'tipo' => 'periferico', 'precio' => 29],
        ];
        $tipo = $request->query('tipo');

        // El ternario conserva todos los elementos si no se ha elegido tipo.
        $filtrados = $tipo
            ? array_values(array_filter($novedades, fn (array $n): bool => $n['tipo'] === $tipo))
            : $novedades;

        return view('usuario.novedades', ['novedades' => $filtrados, 'tipo' => $tipo]);
    }
}
