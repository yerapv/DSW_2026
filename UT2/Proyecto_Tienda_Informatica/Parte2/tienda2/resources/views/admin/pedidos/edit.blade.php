@extends('layouts.admin')
@section('volver-url', route('admin.pedidos.index'))
@section('title', 'Cambiar estado del pedido')
@section('content')
    <h1>Cambiar estado del pedido #{{ $pedido->id }}</h1>
    <p>Solo se puede cambiar el estado. Los datos del cliente, el método de pago, los productos y los importes se conservan.</p>
    <form method="POST" action="{{ route('admin.pedidos.update', $pedido) }}" class="form admin-form">
        @csrf
        @method('PUT')
        @include('admin.pedidos._form')
        <button>Guardar estado</button>
    </form>
    @include('admin.pedidos._lineas')
@endsection
