@extends('layouts.laboratorio')
@section('laboratorio')
<h1>{{ $libro->titulo }}</h1>
    <p>{{ $libro->autor }} · {{ $libro->anio }}</p>
    <p>{{ $libro->disponible ? 'Disponible' : 'No disponible' }}</p>
        <a href="{{ route('e10.libros.index') }}">Volver</a>
@endsection
