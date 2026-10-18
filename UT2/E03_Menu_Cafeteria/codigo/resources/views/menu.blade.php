<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Menú</title>
</head>

<body>
    <h1>Menú</h1>
    <ul>
        @foreach($productos as $producto)
        <li>
            {{ $producto['nombre'] }} — {{ number_format($producto['precio'],2) }} €
            @if(!$producto['disponible']) <strong>(no disponible)</strong> @endif
        </li>
        @endforeach
    </ul>
    <p>Disponibles: {{ $disponibles }}</p>
</body>

</html>