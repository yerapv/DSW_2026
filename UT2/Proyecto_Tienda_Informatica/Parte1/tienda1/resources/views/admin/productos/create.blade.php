@extends('layouts.admin')
@section('volver-url', route('admin.productos.index'))
@section('title', 'Nuevo producto')
@section('content')
    <h1>Nuevo producto</h1><form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data" class="form admin-form">
        @include('admin.productos._form')
    </form>
@endsection
