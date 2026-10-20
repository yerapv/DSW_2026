@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Agenda cultural</h1>
    <form method="GET" action="/e04/eventos">
        <select name="tipo">
            <option value="">Todos</option>
            @foreach(['teatro'=>'Teatro','musica'=>'Música','cine'=>'Cine'] as $valor=>$texto)
            <option value="{{ $valor }}" @selected($tipo===$valor)>{{ $texto }}</option>
            @endforeach
        </select>
        <button>Filtrar</button>
    </form>
    <ul>@foreach($eventos as $evento)<li>{{ $evento['nombre'] }} — {{ $evento['tipo'] }} — {{ $evento['precio'] }} €</li>@endforeach</ul>
@endsection
