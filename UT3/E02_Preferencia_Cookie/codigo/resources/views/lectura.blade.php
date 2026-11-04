<!doctype html>
<html>
<body>
    <h1>Panel de lectura</h1>
    <p>Tema actual: {{ $tema }}</p>
    <form method="POST" action="{{ route('tema.store') }}">
        @csrf
        <select name="tema">
            <option>claro</option>
            <option>oscuro</option>
        </select><button>Guardar</button></form>

    <form method="POST" action="{{ route('tema.destroy') }}">
        @csrf
        @method('DELETE')
        <button>Olvidar</button>
    </form>
</body>
</html>
