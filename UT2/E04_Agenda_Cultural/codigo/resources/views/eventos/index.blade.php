<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Agenda</title>
</head>

<body>
    <h1>Agenda cultural</h1>
    <form method="GET" action="/eventos">
        <select name="tipo">
            <option value="">Todos</option>
            @foreach(['teatro'=>'Teatro','musica'=>'Música','cine'=>'Cine'] as $valor=>$texto)
            <option value="{{ $valor }}" @selected($tipo===$valor)>{{ $texto }}</option>
            @endforeach
        </select>
        <button>Filtrar</button>
    </form>
    <ul>@foreach($eventos as $evento)<li>{{ $evento['nombre'] }} — {{ $evento['tipo'] }} — {{ $evento['precio'] }} €</li>@endforeach</ul>
</body>

</html>