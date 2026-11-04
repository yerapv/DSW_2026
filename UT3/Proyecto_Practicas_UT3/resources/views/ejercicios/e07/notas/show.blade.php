@extends('layouts.laboratorio')
@section('laboratorio')
    <h1>{{ $nota->titulo }}</h1>
    <p>{{ $nota->contenido }}</p>
    @can('update', $nota)
        <h2>Editar nota</h2>
        <form method="POST" action="{{ route('e07.notas.update', $nota) }}">
            @csrf
            @method('PUT')
            <label for="titulo">Título</label>
            <input id="titulo" name="titulo" maxlength="120" value="{{ old('titulo', $nota->titulo) }}" required>
            <label for="contenido-nota">Contenido</label>
            <textarea id="contenido-nota" name="contenido" required>{{ old('contenido', $nota->contenido) }}</textarea>
            <button>Guardar cambios</button>
        </form>
    @endcan
    @can('delete', $nota)
        <form method="POST" action="{{ route('e07.notas.destroy', $nota) }}">
            @csrf
            @method('DELETE')
            <button>Eliminar nota</button>
        </form>
    @endcan
    <a href="{{ route('e07.notas.index') }}">Volver a mis notas</a>
@endsection
