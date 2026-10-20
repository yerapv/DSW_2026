<?php

namespace App\Ejercicios\E08\Http\Controllers;

use App\Ejercicios\E08\Http\Requests\GuardarMascotaRequest;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MascotaController extends Controller
{
    public function create(): View
    {
        return view('ejercicios.e08.mascotas.create');
    }

    public function store(GuardarMascotaRequest $request): View
    {
        return view('ejercicios.e08.mascotas.show', ['mascota' => $request->validated()]);
    }
}
