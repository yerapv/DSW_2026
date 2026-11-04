<!doctype html>
<html>

<body>
    <h1>Mis notas</h1>
    <form method="POST" action="/notas">
        @csrf
        <input name="titulo">
        <textarea name="contenido"></textarea>
        <button>Crear</button>
    </form>
    @foreach ($notas as $nota)
    <p><a href="/notas/{{ $nota->id }}">{{ $nota->titulo }}</a></p>
    @endforeach
</body>

</html>
