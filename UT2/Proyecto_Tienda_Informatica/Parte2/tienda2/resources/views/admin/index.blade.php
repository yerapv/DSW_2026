@extends('layouts.admin')
@section('volver-url', route('inicio'))
@section('title', 'Administración')
@section('content')
    <p class="eyebrow">GESTIÓN DE {{ config('tienda.nombre') }}</p>
    <h1>Panel de administración</h1>
    <p class="lead">Selecciona el módulo que quieres gestionar.</p>
    <div class="table-wrap">
        <table class="management-table" aria-label="Módulos de administración">
            <thead><tr><th scope="col">Módulo</th><th scope="col">Contenido</th><th scope="col">Acceso</th></tr></thead>
            <tbody>
                <tr><th scope="row">Productos</th><td>Catálogo, características y fotografías.</td><td><a class="button small" href="{{ route('admin.productos.index') }}">Gestionar productos</a></td></tr>
                <tr><th scope="row">Avisos</th><td>Publicaciones del tablón de la tienda.</td><td><a class="button small" href="{{ route('admin.avisos.index') }}">Gestionar avisos</a></td></tr>
                <tr><th scope="row">Escaparate</th><td>Fotografías, nombres y autores.</td><td><a class="button small" href="{{ route('admin.imagenes.index') }}">Gestionar escaparate</a></td></tr>
                <tr><th scope="row">Clientes</th><td>Datos de contacto para las compras.</td><td><a class="button small" href="{{ route('admin.clientes.index') }}">Gestionar clientes</a></td></tr>
                <tr><th scope="row">Pedidos</th><td>Alta, consulta, edición del estado y eliminación.</td><td><a class="button small" href="{{ route('admin.pedidos.index') }}">Gestionar pedidos</a></td></tr>
                <tr><th scope="row">Métodos de pago</th><td>Opciones disponibles para los pagos simulados.</td><td><a class="button small" href="{{ route('admin.metodos-pago.index') }}">Gestionar métodos</a></td></tr>
                <tr><th scope="row">Histórico de pedidos</th><td>Consulta de compras, clientes, importes y estados.</td><td><a class="button small" href="{{ route('admin.pedidos.historico') }}">Consultar histórico</a></td></tr>
            </tbody>
        </table>
    </div>
@endsection
