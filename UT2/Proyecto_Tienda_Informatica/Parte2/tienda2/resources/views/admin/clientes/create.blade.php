@extends('layouts.admin')
@section('volver-url', route('admin.clientes.index'))
@section('title', 'Nuevo cliente')
@section('content')
    <h1>Nuevo cliente</h1>
    <form method="POST" action="{{ route('admin.clientes.store') }}" class="form admin-form">@csrf
        @include('admin.clientes._form')
    </form>
@endsection
