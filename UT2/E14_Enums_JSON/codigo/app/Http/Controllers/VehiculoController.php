<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    public function index(): View
    {
        return view('vehiculos.index', ['vehiculos' => Vehiculo::latest()->get()]);
    }
    public function create(): View
    {
        return view('vehiculos.create');
    }
    public function store(Request $r): RedirectResponse
    {
        $d = $r->validate(['nombre' => ['required'], 'matricula' => ['required', 'unique:vehiculos,matricula'], 'tipo' => ['required', 'in:coche,moto,furgoneta'], 'plazas' => ['nullable', 'integer', 'min:1'], 'cilindrada' => ['nullable', 'integer', 'min:50']], [
            'nombre.required' => 'Indica el nombre del vehículo.',
            'matricula.required' => 'Indica la matrícula del vehículo.',
            'tipo.required' => 'Selecciona el tipo de vehículo.',
        ]);
        $car = [];
        if (in_array($d['tipo'], ['coche', 'furgoneta'], true)) {
            $car['plazas'] = $d['plazas'] ?? null;
        }
        if ($d['tipo'] === 'moto') {
            $car['cilindrada'] = $d['cilindrada'] ?? null;
        }
        Vehiculo::create(['nombre' => $d['nombre'], 'matricula' => $d['matricula'], 'tipo' => $d['tipo'], 'caracteristicas' => $car]);
        return redirect('/vehiculos');
    }
}
