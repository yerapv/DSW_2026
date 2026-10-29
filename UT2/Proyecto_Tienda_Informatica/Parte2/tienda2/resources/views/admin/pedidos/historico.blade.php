@extends('layouts.admin')
@section('title', 'Histórico de pedidos')
@section('content')
    <h1>Histórico de pedidos</h1>
    <p class="lead">Consulta los datos guardados de las compras y sus estados actuales.</p>
    @include('admin.pedidos._filtros', ['destino' => route('admin.pedidos.historico')])
    @include('admin.pedidos._tabla', ['soloLectura' => true])
@endsection
