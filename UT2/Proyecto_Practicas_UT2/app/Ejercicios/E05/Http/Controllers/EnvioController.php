<?php

namespace App\Ejercicios\E05\Http\Controllers;

use App\Ejercicios\E05\Dominio\Envios\CalculadoraEnvio;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class EnvioController extends Controller
{
    public function index(): View
    {
        $calculadora = new CalculadoraEnvio;

        return view('ejercicios.e05.envios', [
            'normal' => $calculadora->calcular(2.5, false),
            'urgente' => $calculadora->calcular(2.5, true),
        ]);
    }
}
