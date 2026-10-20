@extends('layouts.laboratorio')
@section('laboratorio')
<h1>{{ $resultado['valid'] ? 'Solicitud válida' : 'Solicitud no válida' }}</h1>
    <h2>Errores</h2>
    <ul>
        @foreach ($resultado['errors'] as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
    <h2>Avisos</h2>
    <ul>
        @foreach ($resultado['warnings'] as $w)
            <li>{{ $w }}</li>
        @endforeach
    </ul>
@endsection
