<?php

namespace App\Ejercicios\E01\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BienvenidaController extends Controller
{
    public function index(): View
    {
        return view('ejercicios.e01.bienvenida', [
            'titulo' => 'CIFP VILLA DE AGüIMES',
            'mensaje' => 'Bienvenido a nuestra programación de actividades.',
        ]);
    }
}
