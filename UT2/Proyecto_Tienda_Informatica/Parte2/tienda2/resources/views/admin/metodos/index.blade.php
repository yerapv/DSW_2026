@extends('layouts.admin')
@section('title', 'Métodos de pago')
@section('content')
    <div class="section-heading"><h1>Gestionar métodos de pago</h1><a class="button" href="{{ route('admin.metodos-pago.create') }}">Nuevo método</a></div>
    <div class="table-wrap"><table class="management-table" aria-label="Listado de métodos de pago">
        <thead><tr><th scope="col">Nombre</th><th scope="col">Código</th><th scope="col">Activo</th><th scope="col">Acciones</th></tr></thead>
        <tbody>@forelse ($metodos as $metodo)
            <tr><th scope="row">{{ $metodo->nombre }}</th><td>{{ $metodo->codigo }}</td><td>{{ $metodo->activo ? 'Sí' : 'No' }}</td><td>@include('admin.partials.acciones', ['recurso' => 'metodos-pago', 'registro' => $metodo])</td></tr>
        @empty<tr><td colspan="4">No hay métodos de pago.</td></tr>@endforelse</tbody>
    </table></div>
    <div class="pagination">{{ $metodos->links('pagination::bootstrap-4') }}</div>
@endsection
