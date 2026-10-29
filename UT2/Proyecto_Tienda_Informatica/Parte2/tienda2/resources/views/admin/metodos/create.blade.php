@extends('layouts.admin')
@section('volver-url', route('admin.metodos-pago.index'))
@section('title', 'Nuevo método de pago')
@section('content')
    <h1>Nuevo método de pago</h1>
    <form method="POST" action="{{ route('admin.metodos-pago.store') }}" class="form admin-form">@csrf
        @include('admin.metodos._form')
    </form>
@endsection
