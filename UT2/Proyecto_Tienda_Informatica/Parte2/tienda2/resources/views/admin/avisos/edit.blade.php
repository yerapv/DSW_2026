@extends('layouts.admin')
@section('volver-url', route('admin.avisos.index'))
@section('title', 'Editar aviso')
@section('content')
    <h1>Editar aviso</h1>
    <form method="POST" action="{{ route('admin.avisos.update', $aviso) }}" class="form admin-form">@csrf
        @method('PUT')
        @include('admin.avisos._form')
    </form>
@endsection
