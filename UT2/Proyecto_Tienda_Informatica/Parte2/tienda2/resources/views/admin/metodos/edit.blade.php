@extends('layouts.admin')
@section('volver-url', route('admin.metodos-pago.index'))
@section('title', 'Editar método de pago')
@section('content')
    <h1>Editar método de pago</h1>
    <form method="POST" action="{{ route('admin.metodos-pago.update', $metodo) }}" class="form admin-form">@csrf
        @method('PUT')
        @include('admin.metodos._form')
    </form>
@endsection
