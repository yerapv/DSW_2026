@extends('layouts.laboratorio')
@section('laboratorio')
<div class="introduccion">
    <p class="etiqueta">7 ejercicios · Laravel</p>
    <h1>Ejercicios de la UT3</h1>
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
<aside class="ayuda">
    <h2>Probar registro, acceso y permisos</h2>
    <p>E04–E07 comparten las cuentas y la sesión de acceso. Crea tu cuenta en E04 o inicia sesión en E05 con una cuenta de prácticas.</p>
    <p><strong>Socio:</strong> <code>socio@test.test</code> · <strong>Gestor:</strong> <code>gestor@test.test</code> · <strong>Otro socio:</strong> <code>otra@test.test</code></p>
    <p>Contraseña de las tres cuentas: <code>password</code>. El socio recibe un 403 al entrar en E06; el gestor puede acceder. En E07 cada cuenta solo puede consultar, editar y borrar sus propias notas.</p>
    <p>Para probar el registro de E04 con otra cuenta, cierra antes la sesión desde el perfil.</p>
</aside>
@endsection
