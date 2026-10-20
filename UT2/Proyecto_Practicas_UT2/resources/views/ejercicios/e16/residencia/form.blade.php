@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Solicitud</h1>
    <form method="POST" action="/e16/residencia">@csrf<input type="number" name="edad" placeholder="Edad"><input
            type="number" name="renta" placeholder="Renta"><input type="number" step="0.1" name="distancia_km"
            placeholder="Distancia"><label><input type="checkbox" name="documentacion_completa" value="1">
            Documentación completa</label><button>Evaluar</button></form>
@endsection
