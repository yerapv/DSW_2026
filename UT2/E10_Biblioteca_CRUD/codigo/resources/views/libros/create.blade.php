<!doctype html>
<html lang="es">

<body>
    <h1>Nuevo libro</h1>
    <form method="POST" action="{{ route('libros.store') }}">
        @include('libros._form')
    </form>
</body>

</html>