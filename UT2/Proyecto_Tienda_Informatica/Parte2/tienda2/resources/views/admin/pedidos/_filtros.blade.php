<form method="GET" action="{{ $destino }}" class="filters">
    <label>Cliente<select name="cliente_id"><option value="">Todos los clientes</option>@foreach ($clientes as $cliente)<option value="{{ $cliente->id }}" @selected(request('cliente_id') == $cliente->id)>{{ $cliente->nombre }}</option>@endforeach</select></label>
    <label>Estado<select name="estado"><option value="">Todos los estados</option>@foreach ($estados as $estado)<option value="{{ $estado->value }}" @selected((string) request('estado') === (string) $estado->value)>{{ $estado->nombre() }}</option>@endforeach</select></label>
    <button>Filtrar</button><a href="{{ $destino }}">Limpiar</a>
</form>
