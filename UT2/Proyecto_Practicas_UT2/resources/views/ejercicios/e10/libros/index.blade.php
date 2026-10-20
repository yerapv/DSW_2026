@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Libros</h1><a href="{{ route('e10.libros.create') }}">Nuevo</a>
    @if (session('ok'))
        <p>{{ session('ok') }}</p>
    @endif
    <ul>
        @foreach ($libros as $libro)
            <li><a href="{{ route('e10.libros.show', $libro) }}">{{ $libro->titulo }}</a> — <a
                    href="{{ route('e10.libros.edit', $libro) }}">Editar</a>
                <form method="POST" action="{{ route('e10.libros.destroy', $libro) }}" style="display:inline">@csrf
                    @method('DELETE')<button>Eliminar</button></form>
            </li>
        @endforeach
    </ul>
@endsection
