@extends('layouts.admin')
@section('volver-url', route('admin.imagenes.index'))
@section('title', $imagen->nombre)
@section('content')
    <h1>{{ $imagen->nombre }}</h1>
    <div class="table-wrap">
        <table class="management-table" aria-label="Detalle de la fotografía">
            <tbody>
                <tr><th scope="row">Fotografía</th><td><img class="admin-preview" src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($imagen->ruta_imagen) }}" alt="{{ $imagen->nombre }}"></td></tr>
                <tr><th scope="row">Autor</th><td>{{ $imagen->autor }}</td></tr>
            </tbody>
        </table>
    </div>
    <div class="actions">
        <a class="button" href="{{ route('admin.imagenes.edit', $imagen) }}">Editar</a>
        <form method="POST" action="{{ route('admin.imagenes.destroy', $imagen) }}">@csrf @method('DELETE')<button class="danger">Eliminar</button></form>
    </div>
@endsection
