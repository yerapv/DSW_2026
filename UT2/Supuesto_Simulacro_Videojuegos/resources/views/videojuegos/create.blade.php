@extends('layouts.app')
@section('content')
<h1>Añadir videojuego</h1>
{{-- POST llama a store; multipart/form-data permite enviar la imagen junto a los datos. --}}
<form method="POST" action="{{ route('videojuegos.store') }}" enctype="multipart/form-data">
    {{-- Añade el token que Laravel comprueba para proteger el envío del formulario. --}}
    @csrf
    {{-- Reutiliza el mismo formulario de campos que la edición. --}}
    @include('videojuegos._form')
</form>
@endsection
