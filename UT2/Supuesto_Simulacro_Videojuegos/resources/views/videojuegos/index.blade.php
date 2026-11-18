@extends('layouts.app')
@section('content')
<h1>Videojuegos</h1>
{{-- GET envía el género en la URL y llama a index para filtrar la consulta. --}}
<form method="GET" action="{{ route('videojuegos.index') }}">
    <label>Filtrar por género
        <select name="genero_id">
            <option value="">Todos los géneros</option>
            {{-- El controlador entrega todos los géneros para completar el desplegable. --}}
            @foreach ($generos as $genero)
                {{-- selected conserva el género elegido después de enviar el filtro. --}}
                <option value="{{ $genero->id }}" @selected((string) request('genero_id') === (string) $genero->id)>{{ $genero->nombre }}</option>
            @endforeach
        </select>
    </label>
    <button type="submit">Filtrar</button>
    {{-- La ruta sin parámetros recupera el listado completo. --}}
    <a href="{{ route('videojuegos.index') }}">Limpiar filtro</a>
</form>
<p>
<a class="boton" href="{{ route('videojuegos.create') }}">Añadir videojuego</a>
</p>
<div class="tabla">
    <table>
        <thead>
            <tr><th>Portada</th><th>Título</th><th>Género</th><th>Precio</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            {{-- forelse recorre la colección y permite mostrar un mensaje si está vacía. --}}
            @forelse ($videojuegos as $videojuego)
                <tr>
                    {{-- asset construye la URL pública; storage:link hace accesible el archivo. --}}
                    <td><img src="{{ asset('storage/'.$videojuego->imagen) }}" alt="Portada de {{ $videojuego->titulo }}"></td>
                    <td>{{ $videojuego->titulo }}</td>
                    {{-- genero utiliza la relación belongsTo definida en el modelo Videojuego. --}}
                    <td>{{ $videojuego->genero->nombre }}</td>
                    <td>{{ number_format($videojuego->precio, 2, ',', '.') }} €</td>
                    <td>
                        <a href="{{ route('videojuegos.edit', $videojuego) }}">Editar</a>
                        <form method="POST" action="{{ route('videojuegos.destroy', $videojuego) }}">
                            {{-- CSRF protege la petición y method indica a Laravel que es un DELETE. --}}
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="borrar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                {{-- Distingue entre un listado sin registros y un género sin coincidencias. --}}
                <tr><td colspan="5">{{ request('genero_id') !== null && request('genero_id') !== '' ? 'No hay videojuegos de este género.' : 'Todavía no hay videojuegos.' }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
