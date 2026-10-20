<?php

namespace App\Ejercicios\E02\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PeliculaController extends Controller
{
    public function show(): View
    {
        $duracion = 117;

        return view('ejercicios.e02.pelicula', [
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
