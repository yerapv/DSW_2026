@extends('layouts.usuario')
@section('volver-url', route('inicio', ['cliente_id' => request('cliente_id')]))
@section('title', 'Escaparate')
@section('content')
    <p class="eyebrow">EN IMÁGENES</p><h1>Escaparate</h1>
    <div class="grid three">
        @forelse ($imagenes as $imagen)
            <article class="card">
                <img class="gallery-image" src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($imagen->ruta_imagen) }}" alt="{{ $imagen->nombre }}">
                <h2>{{ $imagen->nombre }}</h2><p>{{ $imagen->autor }}</p>
            </article>
        @empty<div class="card">Aún no hay fotografías en el escaparate.</div>@endforelse
    </div>
@endsection
