@extends('layouts.laboratorio')
@section('laboratorio')
    <h1>Acceso</h1>
    <form method="POST" action="{{ route('e05.login.store') }}">
        @csrf
        <div class="campo"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required></div>
        <div class="campo"><label for="password">Contraseña</label><input id="password" type="password" name="password" autocomplete="current-password" required></div>
        <label><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Recordarme</label>
        <button>Entrar</button>
    </form>
    <a href="{{ route('e04.registro') }}">Crear una cuenta de socio</a>
@endsection
