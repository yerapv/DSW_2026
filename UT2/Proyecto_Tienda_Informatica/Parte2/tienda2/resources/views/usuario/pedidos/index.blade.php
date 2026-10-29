@extends('layouts.usuario')
@section('volver-url', route('usuario.index', ['cliente_id' => request('cliente_id')]))
@section('title', 'Histórico de pedidos')
@section('content')
    <p class="eyebrow">COMPRAS GUARDADAS</p><h1>Histórico de pedidos</h1>
    <p class="lead">{{ $cliente ? 'Pedidos de '.$cliente->nombre : 'Histórico compartido de todos los clientes' }}</p>
    <form method="GET" action="{{ route('pedidos.index') }}" class="filters">
        <label>Cliente<select name="cliente_id"><option value="">Todos los clientes</option>
            @foreach ($clientes as $opcion)<option value="{{ $opcion->id }}" @selected($cliente?->id === $opcion->id)>{{ $opcion->nombre }}</option>@endforeach
        </select></label><button>Consultar</button><a href="{{ route('pedidos.index') }}">Ver histórico compartido</a>
    </form>
    <div class="card table-wrap">
        <table><thead><tr><th>Pedido</th><th>Fecha</th><th>Método</th><th>Estado</th><th>Total</th><th>Detalle</th></tr></thead><tbody>
            @forelse ($pedidos as $pedido)
                <tr>
                    <td>#{{ $pedido->id }}</td><td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $pedido->nombreMetodoPago() }}</td>
                    <td><span class="order-status">{{ $pedido->estado->nombre() }}</span></td>
                    <td>{{ number_format($pedido->total_centimos / 100, 2, ',', '.') }} €</td>
                    <td><a href="{{ route('pedidos.show', ['pedido' => $pedido, 'cliente_id' => $cliente?->id]) }}">Ver pedido</a></td>
                </tr>
            @empty<tr><td colspan="6">Todavía no hay pedidos. Completa un pago simulado desde el carrito.</td></tr>@endforelse
        </tbody></table>
    </div>
    <div class="pagination">{{ $pedidos->links('pagination::simple-bootstrap-4') }}</div>
    <a class="button secondary" href="{{ route('productos.index', ['cliente_id' => request('cliente_id')]) }}">Seguir comprando</a>
@endsection
