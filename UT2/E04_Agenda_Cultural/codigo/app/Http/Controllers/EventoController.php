<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class EventoController extends Controller
{
    public function index(Request $request): View
    {
        $eventos = [
            ['nombre' => 'Hamlet', 'tipo' => 'teatro', 'precio' => 15],
            ['nombre' => 'Jazz de otoño', 'tipo' => 'musica', 'precio' => 12],
            ['nombre' => 'Cine clásico', 'tipo' => 'cine', 'precio' => 6],
            ['nombre' => 'Comedia breve', 'tipo' => 'teatro', 'precio' => 10],
        ];
        $tipo = $request->query('tipo');

        $filtrados = $tipo
            ? array_values(array_filter($eventos, fn(array $e): bool => $e['tipo'] === $tipo))
            : $eventos;

        return view('eventos.index', ['eventos' => $filtrados, 'tipo' => $tipo]);
    }
}
