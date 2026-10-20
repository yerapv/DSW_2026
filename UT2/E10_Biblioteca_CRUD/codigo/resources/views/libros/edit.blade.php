<!doctype html>
<html lang="es">

<body>
    <h1>Editar libro</h1>
    <form method="POST" action="{{ route('libros.update', $libro) }}">
        @method('PUT') 
        @include('libros._form')
    </form>
</body>

</html>
