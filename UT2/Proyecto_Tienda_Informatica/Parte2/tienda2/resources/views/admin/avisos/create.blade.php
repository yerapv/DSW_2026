@extends('layouts.admin')
@section('volver-url', route('admin.avisos.index'))
@section('title', 'Nuevo aviso')
@section('content')
    <h1>Publicar un aviso</h1>
    <form method="POST" action="{{ route('admin.avisos.store') }}" class="form admin-form">@csrf
        @include('admin.avisos._form')
    </form>
@endsection
