@extends('layouts.laboratorio')
@section('laboratorio')
    <div class="introduccion">
        <p class="etiqueta">18 ejercicios · Laravel</p>
        <h1>Ejercicios de la UT2</h1>
        <p>Abre cada ejercicio para ver su resultado y probar sus formularios y operaciones.</p>
    </div>
    <div class="rejilla">
        @foreach ($ejercicios as $ejercicio)
            <article class="tarjeta">
                <div class="tarjeta-cabecera">
                    <span class="numero">{{ $ejercicio['id'] }}</span>
                    <span class="tipo">{{ $ejercicio['base_datos'] ? 'Con base de datos' : 'Sin base de datos' }}</span>
                </div>
                <h2>{{ $ejercicio['titulo'] }}</h2>
                <p>{{ $ejercicio['descripcion'] }}</p>
                <a class="abrir" href="{{ url($ejercicio['url']) }}">Abrir {{ $ejercicio['id'] }} <span aria-hidden="true">→</span></a>
            </article>
        @endforeach
    </div>
@endsection
