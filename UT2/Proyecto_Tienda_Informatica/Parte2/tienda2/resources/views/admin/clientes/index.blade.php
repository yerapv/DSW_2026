@extends('layouts.admin')
@section('title', 'Clientes')
@section('content')
    <div class="section-heading"><h1>Gestionar clientes</h1><a class="button" href="{{ route('admin.clientes.create') }}">Nuevo cliente</a></div>
    <div class="table-wrap"><table class="management-table" aria-label="Listado de clientes">
        <thead><tr><th scope="col">Nombre</th><th scope="col">Correo</th><th scope="col">Dirección</th><th scope="col">Acciones</th></tr></thead>
        <tbody>@forelse ($clientes as $cliente)
            <tr><th scope="row">{{ $cliente->nombre }}</th><td>{{ $cliente->email ?? 'No indicado' }}</td><td>{{ $cliente->direccion ?? 'No indicada' }}</td><td>@include('admin.partials.acciones', ['recurso' => 'clientes', 'registro' => $cliente])</td></tr>
        @empty<tr><td colspan="4">No hay clientes.</td></tr>@endforelse</tbody>
    </table></div>
    <div class="pagination">{{ $clientes->links('pagination::bootstrap-4') }}</div>
@endsection
