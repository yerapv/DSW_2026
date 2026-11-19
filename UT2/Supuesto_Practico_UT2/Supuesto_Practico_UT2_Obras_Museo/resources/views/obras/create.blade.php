@extends('layouts.app')

@section('title', 'Añadir obra')

@section('content')
<div class="page-heading">
    <div>
        <h1>Añadir obra</h1>
        <p class="muted">Completa la ficha y selecciona una imagen de la obra.</p>
    </div>
</div>

<div class="panel form-panel">
    <form action="{{  }}" method="POST">
        @csrf
        @include('obras._form', ['textoBoton' => 'Guardar obra'])
    </form>
</div>
@endsection
