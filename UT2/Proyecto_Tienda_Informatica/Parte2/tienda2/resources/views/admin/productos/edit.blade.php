@extends('layouts.admin')
@section('volver-url', route('admin.productos.index'))
@section('title', 'Editar producto')
@section('content')
    <h1>Editar {{ $producto->nombre }}</h1><form method="POST" action="{{ route('admin.productos.update', $producto) }}" enctype="multipart/form-data" class="form admin-form">
        @csrf
        @method('PUT')
        @include('admin.productos._form')
    </form>
@endsection
