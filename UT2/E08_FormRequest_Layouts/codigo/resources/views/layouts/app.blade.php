<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
</head>

<body>
    <header>
        <strong>{{ config('clinica.nombre') }}</strong>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>