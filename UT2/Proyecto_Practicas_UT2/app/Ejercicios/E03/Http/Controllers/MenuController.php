<?php

namespace App\Ejercicios\E03\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $productos = [
            ['nombre' => 'Café', 'categoria' => 'bebida', 'precio' => 1.40, 'disponible' => true],
            ['nombre' => 'Té', 'categoria' => 'bebida', 'precio' => 1.30, 'disponible' => true],
            ['nombre' => 'Bocadillo vegetal', 'categoria' => 'comida', 'precio' => 3.80, 'disponible' => true],
            ['nombre' => 'Tarta de queso', 'categoria' => 'postre', 'precio' => 3.20, 'disponible' => false],
            ['nombre' => 'Zumo', 'categoria' => 'bebida', 'precio' => 2.10, 'disponible' => true],
            ['nombre' => 'Croissant', 'categoria' => 'comida', 'precio' => 1.80, 'disponible' => false],
        ];

        return view('ejercicios.e03.menu', ['productos' => $productos, 'disponibles' => $this->contarDisponibles($productos)]);
    }

    private function contarDisponibles(array $productos): int
    {
        $total = 0;
        foreach ($productos as $producto) {
            if ($producto['disponible']) {
                $total++;
            }
        }

        return $total;
    }
}
