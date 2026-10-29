@extends('layouts.usuario')
@section('volver-url', route('pedidos.index', ['cliente_id' => request('cliente_id', $pedido->cliente_id)]))
@section('title', 'Detalle de pedido')
@section('content')
    <p class="eyebrow">COMPRA GUARDADA</p><h1>Pedido #{{ $pedido->id }}</h1>
    <p>Realizado el {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
    <div class="card table-wrap">
        <span class="order-status">{{ $pedido->estado->nombre() }}</span>
        <p class="spaced">Cliente: {{ $pedido->nombreCliente() }}</p>
        <p>Método: {{ $pedido->nombreMetodoPago() }}</p>
        <table><thead><tr><th>Producto</th><th>Unidades</th><th>Importe de línea</th></tr></thead><tbody>
            @foreach ($pedido->instantanea as $linea)
                <tr><td>{{ $linea['producto'] }}</td><td>{{ $linea['cantidad'] }}</td><td>{{ number_format($linea['precio_centimos'] / 100, 2, ',', '.') }} €</td></tr>
            @endforeach
        </tbody></table>
        <p class="price">Total: {{ number_format($pedido->total_centimos / 100, 2, ',', '.') }} €</p>
    </div>
    <div class="actions"><a class="button" href="{{ route('productos.index', ['cliente_id' => request('cliente_id', $pedido->cliente_id)]) }}">Seguir comprando</a><a href="{{ route('pedidos.index', ['cliente_id' => request('cliente_id', $pedido->cliente_id)]) }}">Histórico de pedidos</a></div>
@endsection
