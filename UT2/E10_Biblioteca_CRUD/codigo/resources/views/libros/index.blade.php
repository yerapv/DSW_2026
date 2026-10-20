<!doctype html>
<html lang="es">

<body>
    <h1>Libros</h1><a href="{{ route('libros.create') }}">Nuevo</a>
    @if (session('ok'))
        <p>{{ session('ok') }}</p>
    @endif
    <ul>
        @foreach ($libros as $libro)
            <li><a href="{{ route('libros.show', $libro) }}">{{ $libro->titulo }}</a> — <a
                    href="{{ route('libros.edit', $libro) }}">Editar</a>
                <form method="POST" action="{{ route('libros.destroy', $libro) }}" style="display:inline">@csrf
                    @method('DELETE')<button>Eliminar</button></form>
            </li>
        @endforeach
    </ul>
</body>

</html>
