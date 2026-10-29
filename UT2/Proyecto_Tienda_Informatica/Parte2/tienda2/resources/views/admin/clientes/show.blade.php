@extends('layouts.admin')
@section('volver-url', route('admin.clientes.index'))
@section('title', $cliente->nombre)
@section('content')
    <h1>{{ $cliente->nombre }}</h1>
    <div class="table-wrap"><table class="management-table" aria-label="Datos del cliente"><tbody>
        <tr><th scope="row">Correo electrónico</th><td>{{ $cliente->email ?? 'No indicado' }}</td></tr>
        <tr><th scope="row">Dirección</th><td>{{ $cliente->direccion ?? 'No indicada' }}</td></tr>
        <tr><th scope="row">Tipo</th><td>{{ $cliente->tipo ? ucfirst($cliente->tipo) : 'Sin indicar' }}</td></tr>
        <tr><th scope="row">Edad</th><td>{{ $cliente->edad ?? 'Sin indicar' }}</td></tr>
    </tbody></table></div>
    <div class="actions"><a href="{{ route('carrito.index', ['cliente_id' => $cliente->id]) }}">Ver carrito y presupuestos del cliente</a>@include('admin.partials.acciones', ['recurso' => 'clientes', 'registro' => $cliente, 'detalle' => true])<a href="{{ route('admin.pedidos.index', ['cliente_id' => $cliente->id]) }}">Ver pedidos del cliente</a></div>
@endsection
