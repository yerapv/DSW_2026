@extends('layouts.admin')
@section('volver-url', route('admin.productos.index'))
@section('title', $producto->nombre)
@section('content')
    <h1>{{ $producto->nombre }}</h1>
    <div class="table-wrap">
        <table class="management-table" aria-label="Detalle del producto">
            <tbody>
                <tr><th scope="row">Fotografía</th><td>@include('partials.foto-producto', ['clase' => 'product-photo-preview'])</td></tr>
                <tr><th scope="row">Categoría</th><td>{{ $producto->categoria->nombre }}</td></tr>
                <tr><th scope="row">Tipo</th><td>{{ $producto->tipo->value }}</td></tr>
                <tr><th scope="row">Marca</th><td>{{ $producto->marca }}</td></tr>
                <tr><th scope="row">Año</th><td>{{ $producto->anio }}</td></tr>
                <tr><th scope="row">Precio</th><td>{{ number_format($producto->precio_centimos / 100, 2, ',', '.') }} €</td></tr>
                <tr><th scope="row">Disponibilidad</th><td>{{ $producto->disponible ? 'Disponible' : 'No disponible' }}</td></tr>
                @forelse ($producto->caracteristicas ?? [] as $clave => $valor)
                    <tr><th scope="row">{{ $clave }}</th><td>{{ $valor ?? 'No indicado' }}</td></tr>
                @empty
                    <tr><th scope="row">Características</th><td>Sin características adicionales.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="actions">
        <a class="button" href="{{ route('admin.productos.edit', $producto) }}">Editar</a>
        <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}">@csrf @method('DELETE')<button class="danger">Eliminar</button></form>
    </div>
@endsection
