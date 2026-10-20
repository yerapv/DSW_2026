<!doctype html>
<html lang="es">

<body>
    <h1>{{ $libro->titulo }}</h1>
    <p>{{ $libro->autor }} · {{ $libro->anio }}</p>
    <p>{{ $libro->disponible ? 'Disponible' : 'No disponible' }}</p>
        <a href="{{ route('libros.index') }}">Volver</a>
</body>

</html>
