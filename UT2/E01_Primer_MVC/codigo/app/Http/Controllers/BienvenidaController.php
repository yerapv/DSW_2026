<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BienvenidaController extends Controller
{
    public function index(): View
    {
        return view('bienvenida', [
            'titulo' => 'CIFP VILLA DE AGüIMES',
            'mensaje' => 'Bienvenido a nuestra programación de actividades.',
        ]);
    }
}
