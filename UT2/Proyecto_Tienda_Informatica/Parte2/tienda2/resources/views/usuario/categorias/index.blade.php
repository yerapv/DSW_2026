@extends('layouts.usuario')
@section('volver-url', route('inicio', ['cliente_id' => request('cliente_id')]))
@section('title', 'Categorías')
@section('content')
    <div class="section-heading"><h1>Encuentra tu categoría</h1></div>
    <div class="grid three">@forelse ($categorias as $categoria)<article class="card"><h2>{{ $categoria->nombre }}</h2><ul class="item-list">@forelse ($categoria->productos as $producto)<li><a href="{{ route('productos.show', ['producto' => $producto, 'cliente_id' => request('cliente_id')]) }}">{{ $producto->nombre }}</a></li>@empty<li>Todavía sin productos.</li>@endforelse</ul></article>@empty<p>No hay categorías.</p>@endforelse</div>
@endsection
