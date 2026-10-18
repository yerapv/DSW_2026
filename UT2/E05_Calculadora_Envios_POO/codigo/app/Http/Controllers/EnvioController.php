<?php

namespace App\Http\Controllers;

use App\Dominio\Envios\CalculadoraEnvio;
use Illuminate\View\View;

class EnvioController extends Controller
{
    public function index(): View
    {
        $calculadora = new CalculadoraEnvio();
        return view('envios', [
            'normal' => $calculadora->calcular(2.5, false),
            'urgente' => $calculadora->calcular(2.5, true),
        ]);
    }
}
