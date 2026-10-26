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
<body class="admin-area">
    <div class="topbar">{{ config('tienda.nombre') }} · Administración</div>
    <header class="header">
        <a class="brand" href="{{ route('admin.index') }}"><span class="brand-mark">N</span><span class="brand-name">{{ config('tienda.nombre') }}</span></a>
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
    <footer>{{ config('tienda.nombre') }} · tienda1 · Administración</footer>
</body>
</html>
