@extends('layouts.admin')
@section('volver-url', route('admin.imagenes.index'))
@section('title', 'Subir fotografía')
@section('content')
    <h1>Añadir al escaparate</h1>
    <form method="POST" action="{{ route('admin.imagenes.store') }}" enctype="multipart/form-data" class="form admin-form">@csrf
        @include('admin.imagenes._form')
    </form>
@endsection
