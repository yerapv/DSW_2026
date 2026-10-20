@extends('layouts.laboratorio')
@section('laboratorio')
<header>
        <strong>{{ config('clinica.nombre') }}</strong>
    </header>
    <main>
        @yield('content')
    </main>
@endsection
