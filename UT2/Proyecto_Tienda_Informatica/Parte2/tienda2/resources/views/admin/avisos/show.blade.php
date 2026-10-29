@extends('layouts.admin')
@section('volver-url', route('admin.avisos.index'))
@section('title', $aviso->titulo)
@section('content')
    <h1>{{ $aviso->titulo }}</h1>
    <div class="table-wrap">
        <table class="management-table" aria-label="Detalle del aviso">
            <tbody>
                <tr><th scope="row">Contenido</th><td class="pre-line">{{ $aviso->contenido }}</td></tr>
                <tr><th scope="row">Fecha</th><td>{{ $aviso->created_at->format('d/m/Y H:i') }}</td></tr>
            </tbody>
        </table>
    </div>
    <div class="actions">
        <a class="button" href="{{ route('admin.avisos.edit', $aviso) }}">Editar</a>
        <form method="POST" action="{{ route('admin.avisos.destroy', $aviso) }}">@csrf @method('DELETE')<button class="danger">Eliminar</button></form>
    </div>
@endsection
