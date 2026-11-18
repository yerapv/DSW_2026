@extends('layouts.app')
@section('content')
<h1>Editar videojuego</h1>
{{-- La ruta recibe el modelo y genera una URL con su id. --}}
<form method="POST" action="{{ route('videojuegos.update', $videojuego) }}" enctype="multipart/form-data">
    @csrf
    {{-- HTML envía POST; este campo oculto hace que Laravel lo interprete como PUT. --}}
    @method('PUT')
    {{-- El parcial recibe $videojuego y $generos de esta vista para precargar los campos. --}}
    @include('videojuegos._form')
</form>
@endsection
