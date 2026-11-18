@extends('layouts.app')

@section('title', 'Obras')

@section('content')
<div class="page-heading">
    <div>
        <h1>Obras del museo</h1>
        <p class="muted">Consulta y gestiona las obras de la colección.</p>
    </div>
    <a class="button" href="{{  }}">Añadir obra</a>
</div>

<div class="panel table-scroll">
    <table>
        <caption>Catálogo de obras</caption>
        <thead>
            <tr>
                <th scope="col">Imagen</th>
                <th scope="col">Título</th>
                <th scope="col">Artista</th>
                <th scope="col">Sala</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ()
            <tr>
                <td>
                    <img class="thumbnail" src="{{ }}" alt="{{  }}" width="88" height="72">
                </td>
                <td><strong>{{ }}</strong></td>
                <td>{{ }}</td>
                <td>{{ $obra->sala->nombre }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ }}" aria-label="Editar {{ }}">Editar</a>
                        <form action="{{  }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="delete" type="submit" aria-label="Eliminar {{  }}">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td class="empty" colspan="5">Todavía no hay obras.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
