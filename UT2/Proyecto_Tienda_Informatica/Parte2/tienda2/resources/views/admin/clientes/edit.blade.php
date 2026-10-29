@extends('layouts.admin')
@section('volver-url', route('admin.clientes.index'))
@section('title', 'Editar cliente')
@section('content')
    <h1>Editar cliente</h1>
    <form method="POST" action="{{ route('admin.clientes.update', $cliente) }}" class="form admin-form">@csrf
        @method('PUT')
        @include('admin.clientes._form')
    </form>
@endsection
