@extends('layouts.admin')
@section('title', 'Escaparate')
@section('content')
    <div class="section-heading">
        <div><p class="eyebrow">EN IMÁGENES</p><h1>Escaparate</h1></div>
        <a class="button" href="{{ route('admin.imagenes.create') }}">Subir fotografía</a>
    </div>
    <div class="table-wrap">
        <table class="management-table" aria-label="Listado de fotografías">
            <thead><tr><th scope="col">Fotografía</th><th scope="col">Nombre</th><th scope="col">Autor</th><th scope="col">Acciones</th></tr></thead>
            <tbody>
                @forelse ($imagenes as $imagen)
                    <tr>
                        <td><img class="admin-thumbnail" src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($imagen->ruta_imagen) }}" alt="{{ $imagen->nombre }}" loading="lazy"></td>
                        <th scope="row"><a href="{{ route('admin.imagenes.show', $imagen) }}">{{ $imagen->nombre }}</a></th><td>{{ $imagen->autor }}</td>
                        <td><div class="row-actions">
                            <a class="button secondary small" href="{{ route('admin.imagenes.show', $imagen) }}">Ver</a>
                            <a class="button secondary small" href="{{ route('admin.imagenes.edit', $imagen) }}" aria-label="Editar {{ $imagen->nombre }}">Editar</a>
                            <form method="POST" action="{{ route('admin.imagenes.destroy', $imagen) }}">@csrf @method('DELETE')<button class="small danger" aria-label="Eliminar {{ $imagen->nombre }}">Eliminar</button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="4">Aún no hay fotografías. Añade la primera al escaparate.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
