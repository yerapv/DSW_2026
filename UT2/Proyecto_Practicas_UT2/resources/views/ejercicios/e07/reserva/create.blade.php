@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Reserva</h1>
    <form method="POST" action="/e07/reserva">@csrf
        <label>Nombre <input name="nombre" value="{{ old('nombre') }}"></label>@error('nombre') <span>{{ $message }}</span> @enderror<br>
        <label>Email <input type="email" name="email" value="{{ old('email') }}"></label>@error('email') <span>{{ $message }}</span> @enderror<br>
        <label>Personas <input type="number" name="personas" value="{{ old('personas') }}"></label>@error('personas') <span>{{ $message }}</span> @enderror<br>
        <label>Fecha <input type="date" name="fecha" value="{{ old('fecha') }}"></label>@error('fecha') <span>{{ $message }}</span> @enderror<br>
        <button>Reservar</button>
    </form>
@endsection
