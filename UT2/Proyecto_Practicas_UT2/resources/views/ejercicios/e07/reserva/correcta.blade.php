@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Resultado</h1>@if(session('ok'))<p>{{ session('ok') }}</p>@endif
@endsection
