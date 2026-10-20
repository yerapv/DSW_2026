<!doctype html>
<html lang="es">

<body>
    <h1>Vehículos</h1><a href="/vehiculos/nuevo">Nuevo</a>
    <ul>
        @foreach ($vehiculos as $v)
            <li>{{ $v->nombre }} ({{ $v->tipo->value }}) — {{ $v->matricula }} 
                @if ($v->tipo->value === 'moto')
                    · {{ $v->caracteristicas['cilindrada'] ?? '?' }} cc
                @else
                    · {{ $v->caracteristicas['plazas'] ?? '?' }} plazas
                @endif
            </li>
        @endforeach
    </ul>
</body>

</html>
