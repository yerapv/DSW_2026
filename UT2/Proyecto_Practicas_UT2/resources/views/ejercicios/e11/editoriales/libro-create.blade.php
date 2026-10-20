@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Nuevo libro</h1>
    <form method="POST" action="/e11/editoriales/libros">@csrf<input name="titulo" value="{{ old('titulo') }}"><select name="editorial_id">
            @foreach ($editoriales as $e)
            <option value="{{ $e->id }}">{{ $e->nombre }}</option>
            @endforeach
        </select><button>Guardar</button></form>
@endsection
