@extends('layouts.laboratorio')
@section('laboratorio')
<h1>403 · Acceso denegado</h1>
<p>Tu cuenta no tiene permiso para realizar esta operación.</p>
@if (request()->is('e06/*'))
<p>Solo los gestores pueden entrar en esta zona. Puedes cerrar la sesión y acceder con <code>gestor@test.test</code> (contraseña: <code>password</code>).</p>
@else
<p>Solo el propietario puede acceder a esta nota.</p>
<a href="{{ route('e07.notas.index') }}">Volver a mis notas</a>
@endif
@endsection
