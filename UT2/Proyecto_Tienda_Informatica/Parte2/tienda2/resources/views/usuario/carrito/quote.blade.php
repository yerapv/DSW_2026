@extends('layouts.usuario')
@section('volver-url', route('carrito.index', ['cliente_id' => $presupuesto->cliente_id]))
@section('title', 'Presupuesto histórico')
@section('content')
    <p class="eyebrow">TU SELECCIÓN, GUARDADA</p><h1>Presupuesto #{{ $presupuesto->id }}</h1>
    <p>Cliente: {{ $presupuesto->nombreCliente() }}</p>
    <p>Correo: {{ $presupuesto->datos_cliente['email'] ?? 'No indicado' }}</p>
    <p>Generado el {{ $presupuesto->created_at->format('d/m/Y H:i') }}</p>
    <div class="card table-wrap"><table><thead><tr><th>Producto</th><th>Unidades</th><th>Importe de línea</th></tr></thead><tbody>
        @foreach ($presupuesto->lineas as $linea)<tr><td>{{ $linea->producto_nombre }}</td><td>{{ $linea->cantidad }}</td><td>{{ number_format($linea->precio_centimos / 100, 2, ',', '.') }} €</td></tr>@endforeach
    </tbody></table><p class="price">Total: {{ number_format($presupuesto->total_centimos / 100, 2, ',', '.') }} €</p><p>Estos nombres e importes se conservan aunque cambie el catálogo.</p></div>
    <a href="{{ route('carrito.index', ['cliente_id' => $presupuesto->cliente_id]) }}">Volver al carrito del cliente</a>
@endsection
