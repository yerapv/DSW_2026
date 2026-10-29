{{-- El resumen utiliza las líneas reales del carrito y los importes calculados en PHP. --}}
@extends('layouts.usuario')
@section('volver-url', route('carrito.index', ['cliente_id' => $carrito->cliente_id]))
@section('title', 'Pago simulado')
@section('content')
    <p class="eyebrow">CONFIRMA TU COMPRA</p><h1>Pago simulado</h1>
    <p class="lead">Revisa tu selección y confirma para guardar el pedido en el histórico.</p>
    <div class="card table-wrap">
        <table><thead><tr><th>Producto</th><th>Unidades</th><th>Importe de línea</th></tr></thead><tbody>
            @foreach ($carrito->lineas as $linea)
                <tr><td>{{ $linea->producto->nombre }}</td><td>{{ $linea->cantidad }}</td><td>{{ number_format($linea->precio_centimos / 100, 2, ',', '.') }} €</td></tr>
            @endforeach
        </tbody></table>
        <p class="price">Total: {{ number_format($totalCentimos / 100, 2, ',', '.') }} €</p>
    </div>
    {{-- POST y CSRF: el Service calcula el total y crea la instantánea del pedido. --}}
    <form method="POST" action="{{ route('pago.store') }}" class="card form spaced">
        @csrf
        <input type="hidden" name="cliente_id" value="{{ $carrito->cliente_id }}">
        <p>Cliente: <strong>{{ $carrito->cliente->nombre }}</strong></p>
        <label>Método de pago simulado
            <select name="metodo_pago" required>
                <option value="">Selecciona un método</option>
                @foreach ($metodos as $metodo)
                    <option value="{{ $metodo->codigo }}" @selected(old('metodo_pago') === $metodo->codigo)>{{ $metodo->nombre }}</option>
                @endforeach
            </select>
        </label>
        @error('metodo_pago')<p class="field-error">{{ $message }}</p>@enderror
        @if ($metodos->isEmpty())
            <p>Se necesita un método de pago activo. Puedes gestionarlo desde Admin.</p>
        @endif
        <button @disabled($metodos->isEmpty())>Confirmar pago simulado</button>
    </form>
@endsection
