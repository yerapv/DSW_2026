@extends('layouts.usuario')
@section('volver-url', route('usuario.index', ['cliente_id' => request('cliente_id')]))
@section('title', 'Montaje')
@section('content')
    <p class="eyebrow">PUESTA A PUNTO</p><h1>Calcula tu montaje</h1><p class="lead">25 € por equipo, 10 € extra por instalación y 15 € por encargo urgente.</p>
    <form method="POST" action="{{ route('montaje.calculate', ['cliente_id' => request('cliente_id')]) }}" class="card form">@csrf
        <label>Número de equipos<input type="number" name="equipos" value="{{ old('equipos', 1) }}" min="1" max="100" required></label>
        <label class="check"><input type="checkbox" name="instalacion" value="1" @checked(old('instalacion'))> Instalar sistema por equipo</label>
        <label class="check"><input type="checkbox" name="urgente" value="1" @checked(old('urgente'))> Encargo urgente</label><button>Calcular</button>
    </form>
@endsection
