@extends('layouts.app')

@section('title', 'Editar obra')

@section('content')
<div class="page-heading">
    <div>
        <h1>Editar obra</h1>
        <p class="muted">Actualiza la ficha. Puedes conservar o sustituir la imagen actual.</p>
    </div>
</div>

<div class="panel form-panel">
    <figure class="current-image">
        <img src="{{ }}" alt="{{ }}">
        <figcaption>Imagen actual de {{ }}</figcaption>
    </figure>

    <form action="{{ }}" method="POST">
        @csrf
        @method('PUT')
        @include('obras._form', ['textoBoton' => 'Guardar cambios'])
    </form>
</div>
@endsection
