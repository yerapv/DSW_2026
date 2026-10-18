<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PeliculaController extends Controller
{
    public function show(): View
    {
        $duracion = 117;
        return view('pelicula', [
            'titulo' => 'Alien, el octavo pasajero',
            'anio' => 1979,
            'duracion' => $duracion,
            'precio' => 10.50,
            'disponible' => true,
            'clasificacion' => 18,
            'peliculaLarga' => $duracion > 120,
        ]);
    }
}
