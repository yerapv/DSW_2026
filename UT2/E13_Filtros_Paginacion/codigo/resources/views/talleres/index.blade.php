<!doctype html>
<html lang="es">

<body>
    <h1>Talleres</h1>
    <form method="GET"><input name="q" value="{{ request('q') }}" placeholder="Buscar"><select name="nivel">
            <option value="">Todos</option>
            @foreach (['inicial', 'medio', 'avanzado'] as $n)
                <option @selected(request('nivel') === $n)>{{ $n }}</option>
            @endforeach
        </select>
        <button>Filtrar</button>
    </form>
    <ul>
        @foreach ($talleres as $t)
            <li>{{ $t->titulo }} — {{ $t->nivel }} — Monitor: {{ $t->monitor->nombre }}</li>
        @endforeach
    </ul>{{ $talleres->links() }}
</body>

</html>
