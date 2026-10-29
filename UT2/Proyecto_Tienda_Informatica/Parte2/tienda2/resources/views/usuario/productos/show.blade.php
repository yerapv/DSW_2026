{{-- La ficha pública consulta el mismo modelo que admin, con su propia vista. --}}
@extends('layouts.usuario')
@section('volver-url', route('productos.index', ['cliente_id' => request('cliente_id')]))
@section('title', $producto->nombre)
@section('content')
    @include('usuario.carrito._seleccionar-cliente')
    <p class="eyebrow">{{ $producto->categoria->nombre }}</p><h1>{{ $producto->nombre }}</h1>
    <article class="card detail">
        @include('partials.foto-producto', ['clase' => 'product-photo-detail'])
        
        <span class="badge">{{ $producto->tipo->nombre() }}</span>
        <p class="price">{{ number_format($producto->precio_centimos / 100, 2, ',', '.') }} €</p>
        <dl><dt>Marca</dt><dd>{{ $producto->marca }}</dd><dt>Año</dt><dd>{{ $producto->anio }}</dd><dt>Disponibilidad</dt><dd>{{ $producto->disponible ? 'Disponible' : 'No disponible' }}</dd></dl>
        <h2>Características</h2>
        <dl>@forelse ($producto->caracteristicas ?? [] as $clave => $valor)<dt>{{ $clave }}</dt><dd>{{ $valor ?? 'No indicado' }}</dd>@empty<dd>Sin características adicionales.</dd>@endforelse</dl>
        <form method="POST" action="{{ route('carrito.add') }}" class="purchase-form">
            @csrf
                    <input type="hidden" name="cliente_id" value="{{ $cliente?->id }}">
            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
            <label>Unidades<input type="number" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" max="100" required></label>
            <button @disabled(!$cliente)>Añadir al carrito</button>
        </form>
        <a href="{{ route('productos.index', ['cliente_id' => request('cliente_id')]) }}">Volver al catálogo</a>
    </article>
@endsection
