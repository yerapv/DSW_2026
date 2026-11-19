<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use App\Http\Requests\StoreIncidenciaRequest;
use App\Http\Requests\UpdateIncidenciaRequest;
use App\Http\Requests\DeleteIncidenciaRequest;
use App\Http\Resources\IncidenciaResource;

class IncidenciaController extends Controller
{
    public function index()
    {
        // Completar: devolver todas las incidencias en JSON.
    }

    public function store(StoreIncidenciaRequest $request)
    {
        // Completar: validar, guardar y devolver la incidencia creada.
    }

    public function show(Incidencia $incidencia)
    {
        // Completar: devolver la incidencia recibida en JSON.
    }

    public function update(UpdateIncidenciaRequest $request, Incidencia $incidencia)
    {
        // Completar: validar, actualizar y devolver la incidencia.
    }

    public function destroy(DeleteIncidenciaRequest $request, Incidencia $incidencia)
    {
        // Completar: eliminar y devolver un mensaje JSON.
    }
}
