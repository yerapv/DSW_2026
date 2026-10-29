<?php

namespace App\Http\Controllers;

use App\Dominio\Envios\CalculadoraEnvio;
use Illuminate\View\View;

class EnvioController extends Controller
{
    /** El mismo objeto calcula dos tarifas para un paquete de 2,5 kg. */
    public function index(): View
    {
        $calculadora = new CalculadoraEnvio();

        return view('usuario.envios', [
            'normal' => $calculadora->calcular(2.5, false),
            'urgente' => $calculadora->calcular(2.5, true),
        ]);
    }
}
