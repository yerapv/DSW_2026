@extends('layouts.usuario')
@section('volver-url', route('montaje.create', ['cliente_id' => request('cliente_id')]))
@section('title', 'Resultado del montaje')
@section('content')
    <h1>Presupuesto de montaje</h1><div class="card"><p>Total del servicio</p><p class="price">{{ number_format($totalCentimos / 100, 2, ',', '.') }} €</p><a href="{{ route('montaje.create', ['cliente_id' => request('cliente_id')]) }}">Volver a calcular</a></div>
@endsection
