<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- @yield muestra el título de la hija, o el valor predeterminado. --}}
    <title>@yield('title', 'Inicio') · {{ config('tienda.nombre') }}</title>
    {{-- CSS público directo: estas prácticas de Blade no necesitan compilar JavaScript. --}}
    <link rel="stylesheet" href="{{ asset('css/tienda.css') }}">
</head>
<body class="@yield('area-class', 'usuario-area')">
    <div class="topbar">@yield('topbar', 'Tecnología para estudiar, crear y conectar.')</div>
    <header class="header">
        <a class="brand" href="{{ route('inicio', ['cliente_id' => request('cliente_id')]) }}"><span class="brand-mark">N</span><span class="brand-name">{{ config('tienda.nombre') }}</span></a>
        {{-- Accesos de la derecha: navegar entre áreas no requiere iniciar sesión. --}}
        <nav class="header-actions" aria-label="Accesos de usuario, administración y carrito">
            <a href="{{ route('usuario.index', ['cliente_id' => request('cliente_id')]) }}" @if(request()->routeIs('usuario.index')) aria-current="page" @endif>Usuario</a>
            <a href="{{ route('admin.index') }}" class="button secondary small" @if(request()->routeIs('admin.*')) aria-current="page" @endif>Admin</a>
            <a href="{{ route('carrito.index', ['cliente_id' => request('cliente_id')]) }}" class="button small" @if(request()->routeIs('carrito.*')) aria-current="page" @endif>Carrito</a>
        </nav>
    </header>
    {{-- El layout de cada área aporta su navegación horizontal. --}}
    <div class="site-navigation">
        <div class="navigation-inner">@yield('navigation')</div>
    </div>
    <div class="shell">
        <main id="contenido">
            @yield('back-button')
            {{-- $errors contiene los errores que Laravel recupera tras la redirección. --}}
            @if ($errors->any())
                <div class="message error" role="alert">
                    <strong>Revisa los datos del formulario:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- Cada vista inserta aquí su contenido mediante @section('content'). --}}
            @yield('content')
        </main>
    </div>
    <footer>{{ config('tienda.nombre') }} · tienda2 · Tienda de informática</footer>
</body>
</html>
