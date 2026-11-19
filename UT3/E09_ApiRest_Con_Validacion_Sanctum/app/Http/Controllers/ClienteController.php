<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteClienteRequest;
use App\Http\Resources\ClienteCollection;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::paginate(5);

        return new ClienteCollection($clientes);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        //
        return new ClienteResource(Cliente::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        // Filtro para incluir facturas
        $filtroFacturas = request()->query('facturas');

        if($filtroFacturas){
            return new ClienteResource($cliente->loadMissing('facturas'));
        }

        return new ClienteResource($cliente);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());

        return new ClienteResource($cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteClienteRequest $request, Cliente $cliente)
    {
        $cliente->delete();

        return response()->noContent();
    }
}
