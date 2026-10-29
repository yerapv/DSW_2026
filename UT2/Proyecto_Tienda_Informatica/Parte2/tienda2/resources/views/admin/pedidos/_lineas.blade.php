<div class="table-wrap"><table class="management-table" aria-label="Líneas guardadas del pedido">
    <thead><tr><th scope="col">Producto</th><th scope="col">Cantidad</th><th scope="col">Importe de línea</th></tr></thead>
    <tbody>@foreach ($pedido->instantanea as $linea)<tr><th scope="row">{{ $linea['producto'] }}</th><td>{{ $linea['cantidad'] }}</td><td>{{ number_format($linea['precio_centimos'] / 100, 2, ',', '.') }} €</td></tr>@endforeach</tbody>
</table></div>
<p class="price">Total: {{ number_format($pedido->total_centimos / 100, 2, ',', '.') }} €</p>
