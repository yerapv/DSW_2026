@extends('layouts.admin')
@section('volver-url', route('admin.metodos-pago.index'))
@section('title', $metodo->nombre)
@section('content')
    <h1>{{ $metodo->nombre }}</h1>
    <div class="table-wrap"><table class="management-table" aria-label="Datos del método de pago"><tbody>
        <tr><th scope="row">Código</th><td>{{ $metodo->codigo }}</td></tr>
        <tr><th scope="row">Activo</th><td>{{ $metodo->activo ? 'Sí' : 'No' }}</td></tr>
    </tbody></table></div>
    <div class="actions">@include('admin.partials.acciones', ['recurso' => 'metodos-pago', 'registro' => $metodo, 'detalle' => true])</div>
@endsection
