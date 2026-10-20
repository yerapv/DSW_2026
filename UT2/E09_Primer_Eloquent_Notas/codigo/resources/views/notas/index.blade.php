<!doctype html>
<html lang="es">

<body>
    <h1>Notas</h1><a href="/notas/nueva">Nueva</a>@if(session('ok'))<p>{{ session('ok') }}</p>@endif<ul>@forelse($notas as $nota)<li><strong>{{ $nota->titulo }}</strong><br>{{ $nota->contenido }}</li>@empty<li>No hay notas.</li>@endforelse</ul>
</body>

</html>