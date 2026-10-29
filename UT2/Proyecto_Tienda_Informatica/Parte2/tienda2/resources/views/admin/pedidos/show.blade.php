@extends('layouts.admin')
@section('volver-url', request('desde') === 'historico' ? route('admin.pedidos.historico') : route('admin.pedidos.index'))
@section('title', 'Detalle del pedido')
@section('content')
    <h1>Pedido #{{ $pedido->id }}</h1>
    <div class="table-wrap"><table class="management-table" aria-label="Datos guardados del pedido"><tbody>
        <tr><th scope="row">Fecha</th><td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr><th scope="row">Cliente</th><td>{{ $pedido->nombreCliente() }}</td></tr>
        <tr><th scope="row">Correo</th><td>{{ $pedido->datos_cliente['email'] ?? 'No indicado' }}</td></tr>
        <tr><th scope="row">Dirección</th><td>{{ $pedido->datos_cliente['direccion'] ?? 'No indicada' }}</td></tr>
        <tr><th scope="row">Método</th><td>{{ $pedido->nombreMetodoPago() }}</td></tr>
        <tr><th scope="row">Estado</th><td>{{ $pedido->estado->nombre() }}</td></tr>
    </tbody></table></div>
    <h2 class="spaced">Líneas del pedido</h2>
    @include('admin.pedidos._lineas')
    <a class="button" href="{{ route('admin.pedidos.edit', $pedido) }}">Cambiar estado</a>
@endsection
