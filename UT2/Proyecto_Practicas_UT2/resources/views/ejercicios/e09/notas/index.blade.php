@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Notas</h1><a href="/e09/notas/nueva">Nueva</a>@if(session('ok'))<p>{{ session('ok') }}</p>@endif<ul>@forelse($notas as $nota)<li><strong>{{ $nota->titulo }}</strong><br>{{ $nota->contenido }}</li>@empty<li>No hay notas.</li>@endforelse</ul>
@endsection
