@extends('ejercicios.e08.layouts.app')
@section('title', 'Ficha')
@section('content')
<h1>{{ $mascota['nombre'] }}</h1>
<p>{{ $mascota['especie'] }}, {{ $mascota['edad'] }} años</p>
<p>Responsable: {{ $mascota['responsable'] }}</p>
@endsection