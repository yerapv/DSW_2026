@extends('layouts.usuario')
@section('volver-url', route('inicio', ['cliente_id' => request('cliente_id')]))
@section('title', 'Avisos')
@section('content')
    <p class="eyebrow">AL DÍA</p><h1>Tablón de avisos</h1>
    @forelse ($avisos as $aviso)
        <article class="card spaced"><h2>{{ $aviso->titulo }}</h2><p class="pre-line">{{ $aviso->contenido }}</p></article>
    @empty<div class="card">Todavía no hay avisos.</div>@endforelse
@endsection
