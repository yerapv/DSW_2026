@extends('layouts.admin')
@section('volver-url', route('admin.imagenes.index'))
@section('title', 'Editar fotografía')
@section('content')
    <h1>Editar fotografía</h1>
    <form method="POST" action="{{ route('admin.imagenes.update', $imagen) }}" enctype="multipart/form-data" class="form admin-form">
        @method('PUT')
        @include('admin.imagenes._form')
    </form>
@endsection
