@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Contador de visitas</h1>
    <p>Visitas en esta sesión: {{ $visitas }}</p>
    <form method="POST" action="{{ route('e01.contador.reset') }}">@csrf<button>Reiniciar</button></form>
@endsection
