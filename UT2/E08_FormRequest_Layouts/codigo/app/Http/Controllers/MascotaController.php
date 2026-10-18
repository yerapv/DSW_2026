<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarMascotaRequest;
use Illuminate\View\View;

class MascotaController extends Controller
{
    public function create(): View
    {
        return view('mascotas.create');
    }

    public function store(GuardarMascotaRequest $request): View
    {
        return view('mascotas.show', ['mascota' => $request->validated()]);
    }
}
