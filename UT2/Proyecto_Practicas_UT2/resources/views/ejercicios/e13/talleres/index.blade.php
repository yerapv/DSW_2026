@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Talleres</h1>
    <form method="GET" action="{{ route('e13.index') }}">
        <label for="busqueda-taller">Título o monitor</label>
        <input id="busqueda-taller" name="q" value="{{ request('q') }}" placeholder="Buscar por título o monitor">
        <label for="nivel-taller">Nivel</label>
        <select id="nivel-taller" name="nivel">
            <option value="">Todos los niveles</option>
            @foreach (['inicial', 'medio', 'avanzado'] as $n)
                <option value="{{ $n }}" @selected(request('nivel') === $n)>{{ $n }}</option>
            @endforeach
        </select>
        <button>Filtrar</button>
    </form>
    <ul>
        @forelse ($talleres as $t)
            <li>{{ $t->titulo }} — {{ $t->nivel }} — Monitor: {{ $t->monitor->nombre }}</li>
        @empty
            <li>No hay talleres que coincidan con los filtros.</li>
        @endforelse
    </ul>{{ $talleres->links() }}
@endsection
