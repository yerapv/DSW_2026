<!doctype html>
<html>
<body>
    <h1>Contador de visitas</h1>
    <p>Visitas en esta sesión: {{ $visitas }}</p>
    <form method="POST" action="{{ route('contador.reset') }}">@csrf<button>Reiniciar</button></form>
</body>
</html>
