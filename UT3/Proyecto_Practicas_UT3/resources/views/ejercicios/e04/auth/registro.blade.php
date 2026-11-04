@extends('layouts.laboratorio')
@section('laboratorio')
    <h1>Registro de socios</h1>
    <form method="POST" action="{{ route('e04.registro.store') }}">
        @csrf
        <div class="campo"><label for="name">Nombre</label><input id="name" name="name" value="{{ old('name') }}" maxlength="100" autocomplete="name" required></div>
        <div class="campo"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></div>
        <div class="campo"><label for="password">Contraseña (mínimo 8 caracteres)</label><input id="password" type="password" name="password" autocomplete="new-password" required></div>
        <div class="campo"><label for="confirmation">Confirmar contraseña</label><input id="confirmation" type="password" name="password_confirmation" autocomplete="new-password" required></div>
        <button>Crear cuenta</button>
    </form>
    <a href="{{ route('e05.login') }}">Ya tengo cuenta</a>
@endsection
