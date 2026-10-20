@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Resultado</h1>
    <p>{{ number_format($totalCentimos / 100, 2) }} €</p>
@endsection
