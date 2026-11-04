<!doctype html>
<html>

<body>
    <h1>Acceso</h1>
    <form method="POST" action="/acceso">
        @csrf
        <input name="email" value="{{ old('email') }}" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <label>
            <input type="checkbox" name="remember">Recordarme
        </label>
        <button>Entrar</button>
    </form>
    @error('email')
    <p>{{ $message }}</p>
    @enderror
</body>

</html>
