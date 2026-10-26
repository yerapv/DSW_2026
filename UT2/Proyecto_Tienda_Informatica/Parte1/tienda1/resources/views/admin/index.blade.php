@extends('layouts.admin')
@section('volver-url', route('admin.productos.index'))
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
            </tbody>
        </table>
    </div>
@endsection
