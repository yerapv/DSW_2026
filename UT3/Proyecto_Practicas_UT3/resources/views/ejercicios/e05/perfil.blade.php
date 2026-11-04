@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Perfil</h1>
    <p>{{ auth()->user()->name }} — {{ auth()->user()->email }}</p>
    <form method="POST" action="{{ route('e05.logout') }}">
        @csrf
        <button>Salir</button>
    </form>
@endsection
