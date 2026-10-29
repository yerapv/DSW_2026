@extends('layouts.usuario')
@section('volver-url', route('usuario.index', ['cliente_id' => $cliente->id]))
@section('title', 'Ficha guardada')
@section('content')
    <h1>Ficha guardada</h1>
    <p class="lead">La información del cliente está guardada y disponible para sus compras.</p>
    <div class="card"><dl>
        <dt>Nombre</dt><dd>{{ $cliente->nombre }}</dd>
        <dt>Tipo</dt><dd>{{ $cliente->tipo ? ucfirst($cliente->tipo) : 'Sin indicar' }}</dd>
        <dt>Edad</dt><dd>{{ $cliente->edad ?? 'Sin indicar' }}</dd>
        <dt>Email</dt><dd>{{ $cliente->email }}</dd>
        <dt>Dirección</dt><dd>{{ $cliente->direccion ?? 'Sin indicar' }}</dd>
    </dl></div>
    <div class="actions">
        <a class="button" href="{{ route('fichas.create', ['cliente_id' => $cliente->id]) }}">Editar ficha</a>
        <a href="{{ route('carrito.index', ['cliente_id' => $cliente->id]) }}">Ir al carrito</a>
        <a href="{{ route('fichas.create') }}">Crear otro cliente</a>
    </div>
@endsection
