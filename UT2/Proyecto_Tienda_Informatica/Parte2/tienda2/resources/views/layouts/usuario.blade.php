@extends('layouts.app')
@section('area-class', 'usuario-area')
@section('navigation')
    <p class="eyebrow">EXPLORA {{ config('tienda.nombre') }}</p>
    <nav aria-label="Secciones de la tienda">
        <a href="{{ route('inicio', ['cliente_id' => request('cliente_id')]) }}">Inicio</a>
        <a href="{{ route('productos.index', ['cliente_id' => request('cliente_id')]) }}">Catálogo completo</a>
        <a href="{{ route('categorias.index', ['cliente_id' => request('cliente_id')]) }}">Por categorías</a>
        <a href="{{ route('novedades', ['cliente_id' => request('cliente_id')]) }}">Novedades</a>
        <a href="{{ route('imagenes.index', ['cliente_id' => request('cliente_id')]) }}">Escaparate</a>
        <a href="{{ route('avisos.index', ['cliente_id' => request('cliente_id')]) }}">Tablón de avisos</a>
    </nav>
@endsection

{{-- Cada vista puede elegir el destino; se usa Inicio como opción predeterminada. --}}
@section('back-button')
    <div class="user-page-actions">
        <a class="button secondary small back-button" href="@yield('volver-url', route('inicio', ['cliente_id' => request('cliente_id')]))">← Volver</a>
    </div>
@endsection
