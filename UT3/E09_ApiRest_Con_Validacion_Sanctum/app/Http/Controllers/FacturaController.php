<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteFacturaRequest;
use App\Http\Resources\FacturaResource;
use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Http\Resources\FacturaCollection;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facturas = Factura::all();

        return new FacturaCollection($facturas);
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
    public function store(StoreFacturaRequest $request)
    {
        return new FacturaResource(Factura::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        return new FacturaResource($factura);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacturaRequest $request, Factura $factura)
    {
        $factura->update($request->validated());

        return new FacturaResource($factura);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteFacturaRequest $request, Factura $factura)
    {
        $factura->delete();

        return response()->noContent();
    }
}
