@extends('layouts.usuario')
@section('volver-url', route('inicio', ['cliente_id' => request('cliente_id')]))
@section('title', 'Novedades')
@section('content')
    <p class="eyebrow">DESCUBRE</p><h1>Novedades</h1>
    <form method="GET" action="{{ route('novedades', ['cliente_id' => request('cliente_id')]) }}" class="filters">
        @if (request()->filled('cliente_id'))<input type="hidden" name="cliente_id" value="{{ request('cliente_id') }}">@endif
        <label>Tipo<select name="tipo"><option value="">Todos</option>
            <option value="portatil" @selected($tipo === 'portatil')>Portátiles</option>
            <option value="periferico" @selected($tipo === 'periferico')>Periféricos</option>
            <option value="componente" @selected($tipo === 'componente')>Componentes</option>
        </select></label><button>Filtrar</button><a href="{{ route('novedades', ['cliente_id' => request('cliente_id')]) }}">Limpiar</a>
    </form>
    {{-- @forelse ofrece un mensaje si el filtro no encuentra resultados. --}}
    <div class="grid three">
        @forelse ($novedades as $novedad)
            <article class="card"><span class="badge">{{ $novedad['tipo'] }}</span><h2>{{ $novedad['nombre'] }}</h2><p class="price">{{ number_format($novedad['precio'], 2, ',', '.') }} €</p></article>
        @empty <p>No hay novedades de ese tipo.</p> @endforelse
    </div>
@endsection
