@extends('layouts.usuario')
@section('volver-url', route('productos.index', ['cliente_id' => request('cliente_id')]))
@section('title', 'Carrito')
@section('content')
    <p class="eyebrow">PREPARA TU SELECCIÓN</p><h1>Carrito y presupuesto</h1>
    @include('usuario.carrito._seleccionar-cliente')
    @if ($cliente)
        <h2>Carrito de {{ $cliente->nombre }}</h2>

        <div class="card table-wrap"><table><thead><tr><th>Producto</th><th>Unidades</th><th>Importe de línea</th></tr></thead><tbody>
            @forelse ($carrito->lineas as $linea)<tr><td>{{ $linea->producto->nombre }}</td><td>{{ $linea->cantidad }}</td><td>{{ number_format($linea->precio_centimos / 100, 2, ',', '.') }} €</td></tr>@empty<tr><td colspan="3">Tu selección está vacía.</td></tr>@endforelse
        </tbody></table><p class="price">Total: {{ number_format($totalCentimos / 100, 2, ',', '.') }} €</p></div>
        <div class="actions">
            @if ($carrito->lineas->isNotEmpty())<a class="button" href="{{ route('pago.create', ['cliente_id' => $cliente->id]) }}">Simular pago</a>@endif
            <a href="{{ route('pedidos.index', ['cliente_id' => request('cliente_id')]) }}">Histórico de pedidos</a>
        </div>
        <form method="POST" action="{{ route('carrito.quote') }}" class="spaced">@csrf
            <input type="hidden" name="cliente_id" value="{{ $cliente->id }}"><button>Generar presupuesto histórico</button>
        </form>
        <h2 class="spaced">Presupuestos de {{ $cliente->nombre }}</h2>
        <div class="card table-wrap"><table><thead><tr><th>Presupuesto</th><th>Fecha</th><th>Total</th><th>Detalle</th></tr></thead><tbody>
            @forelse ($presupuestos as $presupuesto)<tr><td>#{{ $presupuesto->id }}</td><td>{{ $presupuesto->created_at->format('d/m/Y H:i') }}</td><td>{{ number_format($presupuesto->total_centimos / 100, 2, ',', '.') }} €</td><td><a href="{{ route('carrito.presupuestos.show', ['presupuesto' => $presupuesto, 'cliente_id' => $cliente->id]) }}">Ver presupuesto</a></td></tr>@empty<tr><td colspan="4">Este cliente todavía no tiene presupuestos.</td></tr>@endforelse
        </tbody></table></div>
        <div class="pagination">{{ $presupuestos->links('pagination::simple-bootstrap-4') }}</div>
    @else
        <p>Selecciona un cliente para consultar su carrito, añadir productos o generar un presupuesto.</p>
    @endif
@endsection
