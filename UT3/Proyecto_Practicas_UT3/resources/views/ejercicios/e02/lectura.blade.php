@extends('layouts.laboratorio')
@section('laboratorio')
<div class="lectura {{ $tema }}"><h1>Panel de lectura</h1>
    <p>Tema actual: {{ $tema }}</p>
    <form method="POST" action="{{ route('e02.tema.store') }}">
        @csrf
        <label for="tema">Tema de lectura</label>
        <select id="tema" name="tema">
            <option value="claro" @selected($tema === 'claro')>claro</option>
            <option value="oscuro" @selected($tema === 'oscuro')>oscuro</option>
        </select><button>Guardar</button></form>

    <form method="POST" action="{{ route('e02.tema.destroy') }}">
        @csrf
        @method('DELETE')
        <button>Olvidar</button>
    </form>
</div>
@endsection
