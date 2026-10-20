<!doctype html>
<html lang="es">

<body>
    <h1>Editoriales</h1><a href="/editoriales/libros/nuevo">Nuevo libro</a>
    @foreach ($editoriales as $editorial)
    <h2>{{ $editorial->nombre }}</h2>
    <ul>
        @foreach ($editorial->libros as $libro)
        <li>{{ $libro->titulo }}</li>
        @endforeach
    </ul>
    @endforeach
</body>

</html>
