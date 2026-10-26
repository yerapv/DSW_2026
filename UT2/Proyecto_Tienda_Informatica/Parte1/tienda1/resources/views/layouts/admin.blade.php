@extends('layouts.app')
@section('navigation')
    <p class="eyebrow">ADMINISTRACIÓN</p>
    <nav aria-label="Administración">
        <a href="{{ route('admin.index') }}">Panel de administración</a>
        <a href="{{ route('admin.productos.index') }}">Gestionar productos</a>
        <a href="{{ route('admin.avisos.index') }}">Gestionar avisos</a>
        <a href="{{ route('admin.imagenes.index') }}">Gestionar escaparate</a>
    </nav>

@endsection

@section('back-button')
    <div class="admin-page-actions">
        <a class="button secondary small back-button" href="@yield('volver-url', route('admin.index'))">← Volver</a>
    </div>
@endsection
