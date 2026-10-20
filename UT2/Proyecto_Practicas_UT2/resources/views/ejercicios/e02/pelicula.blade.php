@extends('layouts.laboratorio')
@section('laboratorio')
<h1>{{ $titulo }}</h1>
    <ul>
        <li>Año: {{ $anio }}</li>
        <li>Duración: {{ $duracion }} minutos</li>
        <li>Precio: {{ number_format($precio, 2) }} €</li>
        <li>Edad recomendada: {{ $clasificacion }}+</li>
    </ul>
    @if($disponible)
    <p>Entradas disponibles.</p>
    @else
    <p>Agotada.</p>
    @endif
    @if($peliculaLarga)
    <p>Película larga.</p>
    @endif
@endsection
