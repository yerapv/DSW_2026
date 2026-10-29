@extends('layouts.usuario')
@section('volver-url', route('financiacion.create', ['cliente_id' => request('cliente_id')]))
@section('title', 'Resultado de la simulación')
@section('content')
    <h1>Resultado de la simulación</h1><div class="card"><h2>{{ $resultado['valid'] ? 'Solicitud válida para el ejemplo' : 'Solicitud no válida' }}</h2>
        @foreach ($resultado['errors'] as $error)<p class="message error">{{ $error }}</p>@endforeach
        @foreach ($resultado['warnings'] as $warning)<p class="message warning">{{ $warning }}</p>@endforeach
        <a href="{{ route('financiacion.create', ['cliente_id' => request('cliente_id')]) }}">Otra simulación</a>
    </div>
@endsection
