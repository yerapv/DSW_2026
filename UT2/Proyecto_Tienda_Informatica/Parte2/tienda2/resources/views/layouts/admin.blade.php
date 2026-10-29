@extends('layouts.app')
@section('area-class', 'admin-area')
@section('topbar', config('tienda.nombre').' · Administración de la tienda')
@section('navigation')
    <p class="eyebrow">ADMINISTRACIÓN</p>
    <nav aria-label="Administración">
        <a href="{{ route('admin.index') }}">Panel de administración</a>
        <a href="{{ route('admin.productos.index') }}">Gestionar productos</a>
        <a href="{{ route('admin.avisos.index') }}">Gestionar avisos</a>
        <a href="{{ route('admin.imagenes.index') }}">Gestionar escaparate</a>
        <a href="{{ route('admin.clientes.index') }}">Gestionar clientes</a>
        <a href="{{ route('admin.pedidos.index') }}">Gestionar pedidos</a>
        <a href="{{ route('admin.metodos-pago.index') }}">Métodos de pago</a>
        <a href="{{ route('admin.pedidos.historico') }}">Histórico de pedidos</a>
    </nav>

@endsection

@section('back-button')
    <div class="admin-page-actions">
        <a class="button secondary small back-button" href="@yield('volver-url', route('admin.index'))">← Volver</a>
    </div>
@endsection
