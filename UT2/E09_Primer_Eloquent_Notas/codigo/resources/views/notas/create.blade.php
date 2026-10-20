<!doctype html>
<html lang="es">

<body>
    <h1>Nueva nota</h1>
    <form method="POST" action="/notas">@csrf<input name="titulo" value="{{ old('titulo') }}">@error('titulo'){{ $message }}@enderror<br><textarea name="contenido">{{ old('contenido') }}</textarea>@error('contenido'){{ $message }}@enderror<br><button>Guardar</button></form>
</body>

</html>