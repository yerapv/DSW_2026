<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BienvenidaController extends Controller
{
    /** View() recibe el nombre de la plantilla y las variables que utilizará. */
    public function index(): View
    {
        return view('usuario.bienvenida', [
            'titulo' => config('tienda.nombre'),
            'mensaje' => 'Tu próxima idea empieza con el equipo adecuado.',
        ]);
    }
}
