@extends('layouts.laboratorio')
@section('laboratorio')
<h1>{{ $alumno->nombre }}</h1>
    <h2>Actividades</h2>
    <ul>
        @foreach ($alumno->actividades as $a)
            <li>{{ $a->nombre }} ({{ $a->pivot->fecha_inscripcion }}) <form method="POST"
                    action="/e12/alumnos/{{ $alumno->id }}/actividades/{{ $a->id }}" style="display:inline">@csrf
                    @method('DELETE')<button>Quitar</button></form>
            </li>
        @endforeach
    </ul>
    <form method="POST" action="/e12/alumnos/{{ $alumno->id }}/actividades">@csrf<select name="actividad_id">
            @foreach ($actividades as $a)
                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
            @endforeach
        </select><button>Inscribir</button></form>
@endsection
