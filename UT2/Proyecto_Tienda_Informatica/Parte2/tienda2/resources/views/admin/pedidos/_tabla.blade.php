<div class="table-wrap"><table class="management-table" aria-label="Listado de pedidos">
    <thead><tr><th scope="col">Pedido</th><th scope="col">Fecha</th><th scope="col">Cliente</th><th scope="col">Método</th><th scope="col">Estado</th><th scope="col">Total</th><th scope="col">Acciones</th></tr></thead>
    <tbody>@forelse ($pedidos as $pedido)
        <tr><th scope="row">#{{ $pedido->id }}</th><td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td><td>{{ $pedido->nombreCliente() }}</td><td>{{ $pedido->nombreMetodoPago() }}</td><td>{{ $pedido->estado->nombre() }}</td><td class="amount">{{ number_format($pedido->total_centimos / 100, 2, ',', '.') }} €</td>
            <td>@if ($soloLectura ?? false)<a class="button secondary small" href="{{ route('admin.pedidos.show', ['pedido' => $pedido, 'desde' => 'historico']) }}">Ver detalle</a>@else <a class="button secondary small" href="{{ route('admin.pedidos.show', $pedido) }}">Ver detalle</a> <a class="button small" href="{{ route('admin.pedidos.edit', $pedido) }}">Cambiar estado</a> @endif</td></tr>
    @empty<tr><td colspan="7">No hay pedidos con los filtros seleccionados.</td></tr>@endforelse</tbody>
</table></div>
<div class="pagination">{{ $pedidos->links('pagination::bootstrap-4') }}</div>
