<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi colección de videojuegos</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font: 16px/1.5 Arial, sans-serif;
            background: #f3f4f6;
            color: #172033;
            margin: 0;
        }

        header {
            background: #243b64;
            color: white;
            padding: 20px;
        }

        main {
            max-width: 950px;
            margin: 24px auto;
            padding: 20px;
            background: white;
        }

        a {
            color: #254eaa;
        }

        label {
            display: block;
            margin: 16px 0;
            font-weight: bold;
        }

        input,
        select {
            display: block;
            width: 100%;
            max-width: 500px;
            padding: 8px;
            margin-top: 5px;
            font: inherit;
        }

        button,
        .boton {
            background: #254eaa;
            color: white;
            border: 0;
            padding: 9px 14px;
            text-decoration: none;
            font: inherit;
            cursor: pointer;
            display: inline-block;
        }

        .borrar {
            background: #b91c1c;
        }

        .mensaje {
            background: #dcfce7;
            padding: 12px;
        }

        .errores {
            background: #fee2e2;
            padding: 12px;
        }

        .tabla {
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        img {
            width: 80px;
            height: 90px;
            object-fit: contain;
        }

        td form {
            display: inline;
            margin-left: 10px;
        }

        small {
            font-weight: normal;
        }
    </style>
</head>

<body>
    <header>Mi colección de videojuegos</header>
    <main>
        {{-- Muestra un mensaje temporal si el controlador lo guarda en la sesión con la clave success. --}}
        @if (session('success'))
            <p class="mensaje" role="status">{{ session('success') }}</p>
        @endif
        {{-- Laravel entrega aquí los errores de validación para mostrarlos en el formulario. --}}
        @if ($errors->any())
            <div class="errores" role="alert">
                <strong>Revisa los datos:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- Cada vista que extiende este layout coloca aquí su sección content. --}}
        @yield('content')
    </main>
</body>

</html>
