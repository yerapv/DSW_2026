<!doctype html>
<html>

<body>
    <h1>Registro</h1>
    <form method="POST" action="/registro">@csrf
        <input name="name" value="{{ old('name') }}" placeholder="Nombre">
        <input name="email" value="{{ old('email') }}" placeholder="Email">
        <input type="password" name="password" placeholder="Contraseña">
        <input type="password" name="password_confirmation" placeholder="Confirmar contraseña">
        <button>Crear cuenta</button>
    </form>
    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
    @endif
</body>

</html>
