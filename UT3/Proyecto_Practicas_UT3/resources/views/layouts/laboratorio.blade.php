@php
    $ejercicioActual = config('ejercicios.'.request()->segment(1));
@endphp
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $ejercicioActual ? $ejercicioActual['id'].' · '.$ejercicioActual['titulo'] : 'UT3 · Ejercicios Laravel' }}</title>
    <link rel="stylesheet" href="{{ asset('css/laboratorio.css') }}">
</head>
<body>
    <a class="salto" href="#contenido">Saltar al contenido</a>
    <header class="cabecera">
        <a class="marca" href="{{ route('resumen') }}">DSW <span>/ UT3</span></a>
        <nav aria-label="Navegación principal">
            <a href="{{ route('resumen') }}">Todos los ejercicios</a>
            @if ($ejercicioActual)
                <a href="{{ url($ejercicioActual['url']) }}">Inicio de {{ $ejercicioActual['id'] }}</a>
            @endif
        </nav>
    </header>
    <main id="contenido" class="{{ $ejercicioActual ? 'ejercicio' : 'resumen' }}">
        @if ($ejercicioActual)
            <div class="contexto">
                <span class="numero">{{ $ejercicioActual['id'] }}</span>
                <div>
                    <p class="titulo-contexto">{{ $ejercicioActual['titulo'] }}</p>
                    <p>{{ $ejercicioActual['origen'] }}</p>
                </div>
            </div>
            @if ($errors->any())
                <div class="errores" role="alert">
                    <strong>Revisa los datos del formulario.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
        @if ($ejercicioActual && (int) substr($ejercicioActual['id'], 1) >= 4)
            <aside class="cuenta" aria-label="Cuenta de prácticas">
                @auth
                    <p>{{ auth()->user()->name }} · {{ auth()->user()->rol->value }}</p>
                    <a href="{{ route('e05.perfil') }}">Mi perfil</a> ·
                    <a href="{{ route('e06.gestion') }}">Zona de gestión</a> ·
                    <a href="{{ route('e07.notas.index') }}">Mis notas</a>
                    <form method="POST" action="{{ route('e05.logout') }}">@csrf<button>Salir</button></form>
                @else
                    <a href="{{ route('e04.registro') }}">Crear cuenta</a> ·
                    <a href="{{ route('e05.login') }}">Iniciar sesión</a>
                @endauth
            </aside>
        @endif
        @yield('laboratorio')
    </main>
    <footer>Desarrollo web en entorno servidor · Unidad de trabajo 3</footer>
</body>
</html>
