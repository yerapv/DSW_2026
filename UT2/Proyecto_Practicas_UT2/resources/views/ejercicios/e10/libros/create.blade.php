@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Nuevo libro</h1>
    <form method="POST" action="{{ route('e10.libros.store') }}">
        @include('ejercicios.e10.libros._form')
    </form>
@endsection
