@extends('ejercicios.e08.layouts.app')
@section('title', 'Nueva mascota')
@section('content')
<h1>Nueva mascota</h1>
<form method="POST" action="/e08/mascotas">@csrf
    <input name="nombre" placeholder="Nombre" value="{{ old('nombre') }}">
    @error('nombre')
    {{ $message }}
    @enderror
    <br>
    <select name="especie">
        <option value="perro">Perro</option>
        <option value="gato">Gato</option>
        <option value="otro">Otro</option>
    </select><br>
    <input type="number" name="edad" value="{{ old('edad') }}"><br>
    <input name="responsable" placeholder="Responsable" value="{{ old('responsable') }}"><br><button>Continuar</button>
</form>
@endsection