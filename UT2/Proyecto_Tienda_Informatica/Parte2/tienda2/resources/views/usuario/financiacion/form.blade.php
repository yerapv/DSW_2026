@extends('layouts.usuario')
@section('volver-url', route('usuario.index', ['cliente_id' => request('cliente_id')]))
@section('title', 'Simular financiación')
@section('content')
    <p class="eyebrow">PLANIFICA TU COMPRA</p><h1>Simular financiación</h1><p class="lead">Ejemplo con reglas ficticias. No tramita una financiación real.</p>
    <form method="POST" action="{{ route('financiacion.store', ['cliente_id' => request('cliente_id')]) }}" class="card form">@csrf
        <label>Edad<input type="number" name="edad" min="0" max="120" step="1" value="{{ old('edad') }}" required></label>
        <label>Importe solicitado en euros<input type="number" name="importe" min="0" step="0.01" value="{{ old('importe') }}" required></label>
        <label>Antigüedad laboral en meses<input type="number" name="antiguedad_meses" min="0" max="1440" step="1" value="{{ old('antiguedad_meses') }}" required></label>
        <label class="check"><input type="checkbox" name="documentacion_completa" value="1" @checked(old('documentacion_completa'))> Documentación completa</label><button>Comprobar solicitud</button>
    </form>
@endsection
