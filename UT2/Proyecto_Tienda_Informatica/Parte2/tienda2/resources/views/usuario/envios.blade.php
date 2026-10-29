@extends('layouts.usuario')
@section('volver-url', route('usuario.index', ['cliente_id' => request('cliente_id')]))
@section('title', 'Envíos')
@section('content')
    <p class="eyebrow">HASTA TU PUERTA</p><h1>Tarifas de envío</h1>
    <p class="lead">Ejemplo para un paquete de 2,5 kg. Base de 3 € más 0,80 € por kg.</p>
    <div class="grid two"><article class="card"><h2>Normal</h2><p class="price">{{ number_format($normal, 2, ',', '.') }} €</p></article><article class="card"><h2>Urgente</h2><p class="price">{{ number_format($urgente, 2, ',', '.') }} €</p><p>Incluye el suplemento de 5 €.</p></article></div>
@endsection
