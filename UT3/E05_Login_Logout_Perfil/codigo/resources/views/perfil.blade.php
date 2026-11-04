<!doctype html>
<html>

<body>
    <h1>Perfil</h1>
    <p>{{ auth()->user()->name }} — {{ auth()->user()->email }}</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button>Salir</button>
    </form>
</body>

</html>
