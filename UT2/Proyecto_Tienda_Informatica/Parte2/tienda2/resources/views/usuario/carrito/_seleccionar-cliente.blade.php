<form method="GET" action="{{ url()->current() }}" class="filters">
    @foreach (['q', 'tipo'] as $filtro)
        @if (request()->filled($filtro))<input type="hidden" name="{{ $filtro }}" value="{{ request($filtro) }}">@endif
    @endforeach
    <label>Cliente de la compra<select name="cliente_id" required>
        <option value="">Selecciona un cliente</option>
        @foreach ($clientes as $opcion)<option value="{{ $opcion->id }}" @selected($cliente?->id === $opcion->id)>{{ $opcion->nombre }}</option>@endforeach
    </select></label>
    <button>Seleccionar cliente</button>
</form>
@if ($clientes->isEmpty())<p>No hay clientes. Crea uno desde administración para preparar una compra.</p>@endif
