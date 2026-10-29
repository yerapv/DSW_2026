@extends('layouts.admin')
@section('title', 'Gestionar pedidos')
@section('content')
    <h1>Gestionar pedidos</h1>
    @include('admin.pedidos._filtros', ['destino' => route('admin.pedidos.index')])
    @include('admin.pedidos._tabla')
@endsection
