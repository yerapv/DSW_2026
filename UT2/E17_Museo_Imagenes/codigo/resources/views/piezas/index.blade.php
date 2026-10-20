<!doctype html>
<html lang="es">

<body>
    <h1>Piezas</h1><a href="/piezas/nueva">Nueva</a>
    <ul>
        @foreach ($piezas as $p)
            <li><img src="{{ Storage::disk('public')->url($p->ruta_imagen) }}" width="120"
                    alt=""><strong>{{ $p->nombre }}</strong> — {{ $p->autor }}<form method="POST"
                    action="/piezas/{{ $p->id }}">@csrf @method('DELETE')<button>Eliminar</button></form>
            </li>
        @endforeach
    </ul>
</body>

</html>
