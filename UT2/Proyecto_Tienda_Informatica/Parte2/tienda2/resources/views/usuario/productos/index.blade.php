@extends('layouts.usuario')
@section('volver-url', route('inicio', ['cliente_id' => request('cliente_id')]))
@section('title', 'Productos')
@section('content')
    <p class="eyebrow">ELIGE TU PRÓXIMO EQUIPO</p><h1>Catálogo de productos</h1>
    @include('usuario.carrito._seleccionar-cliente')
    <form method="GET" action="{{ route('productos.index', ['cliente_id' => request('cliente_id')]) }}" class="filters">
        <input type="hidden" name="cliente_id" value="{{ $cliente?->id }}">
        <label>Buscar por nombre<input name="q" value="{{ request('q') }}" placeholder="Teclado, portátil..."></label>
        <label>Tipo<select name="tipo"><option value="">Todos los tipos</option>@foreach (\App\Enums\TipoProducto::cases() as $tipo)<option value="{{ $tipo->value }}" @selected((string) request('tipo') === (string) $tipo->value)>{{ $tipo->nombre() }}</option>@endforeach</select></label>
        <button>Buscar</button><a href="{{ route('productos.index', ['cliente_id' => $cliente?->id]) }}">Limpiar</a>
    </form>
    <p class="muted">{{ $productos->total() }} productos encontrados</p>
    <div class="grid two">
        @forelse ($productos as $producto)
            <article class="card product-card">
                @include('partials.foto-producto')
                <span class="badge">{{ $producto->categoria->nombre }}</span>
                <h2><a href="{{ route('productos.show', ['producto' => $producto, 'cliente_id' => $cliente?->id]) }}">{{ $producto->nombre }}</a></h2>
                <p class="muted">{{ $producto->marca }} · {{ $producto->anio }}</p>
                <p class="price">{{ number_format($producto->precio_centimos / 100, 2, ',', '.') }} €</p>
                @if ($producto->disponible)<span class="badge">Disponible</span>@else<span class="badge muted">No disponible</span>@endif
                <div class="actions"><a href="{{ route('productos.show', ['producto' => $producto, 'cliente_id' => $cliente?->id]) }}">Ver ficha</a></div>
                <form method="POST" action="{{ route('carrito.add') }}" class="purchase-form">
                    @csrf
                    <input type="hidden" name="cliente_id" value="{{ $cliente?->id }}">
                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                    <label>Unidades<input type="number" name="cantidad" value="1" min="1" max="100" required></label>
                    <button @disabled(!$cliente)>Añadir al carrito</button>
                </form>
            </article>
        @empty<div class="card">No se han encontrado productos.</div>@endforelse
    </div>
    <div class="pagination">{{ $productos->links('pagination::simple-bootstrap-4') }}</div>

@endsection
