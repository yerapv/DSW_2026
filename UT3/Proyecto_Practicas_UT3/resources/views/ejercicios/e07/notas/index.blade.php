@extends('layouts.laboratorio')
@section('laboratorio')
    <h1>Mis notas</h1>
    <form method="POST" action="{{ route('e07.notas.store') }}">
        @csrf
        <label for="titulo">Título</label>
        <input id="titulo" name="titulo" maxlength="120" value="{{ old('titulo') }}" required>
        <label for="contenido-nota">Contenido</label>
        <textarea id="contenido-nota" name="contenido" required>{{ old('contenido') }}</textarea>
        <button>Crear</button>
    </form>
    @forelse ($notas as $nota)
        <p><a href="{{ route('e07.notas.show', $nota) }}">{{ $nota->titulo }}</a></p>
    @empty
        <p>Todavía no tienes notas.</p>
    @endforelse
@endsection
