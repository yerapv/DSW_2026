@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Editar libro</h1>
    <form method="POST" action="{{ route('e10.libros.update', $libro) }}">
        @method('PUT') 
        @include('ejercicios.e10.libros._form')
    </form>
@endsection
