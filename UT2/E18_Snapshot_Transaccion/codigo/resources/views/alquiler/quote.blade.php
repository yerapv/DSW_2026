<!doctype html>
<html lang="es">

<body>
    <h1>Presupuesto #{{ $presupuesto->id }}</h1>
    <ul>
        @foreach ($presupuesto->instantanea as $l)
            <li>{{ $l['producto'] }} — {{ $l['dias'] }} días — {{ number_format($l['precio_centimos'] / 100, 2) }} €
            </li>
        @endforeach
    </ul><strong>Total: {{ number_format($presupuesto->total_centimos / 100, 2) }} €</strong>
</body>

</html>
