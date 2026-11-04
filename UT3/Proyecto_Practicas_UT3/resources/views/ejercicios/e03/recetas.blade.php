@extends('layouts.laboratorio')
@section('laboratorio')
<h1>Recetas</h1>
    @foreach ($recetas as $id => $nombre)
    <div>{{ $nombre }}
        @if (in_array($id, $favoritos, true))
        <form method="POST" action="{{ route('e03.favorito.remove', $id) }}">
            @csrf
            @method('DELETE')<button>Quitar</button>
        </form>
        @else
        <form method="POST" action="{{ route('e03.favorito.add', $id) }}">
            @csrf
            <button>Favorito</button>
        </form>
        @endif
    </div>
    @endforeach
    <form method="POST" action="{{ route('e03.favoritos.clear') }}">
        @csrf
        @method('DELETE')
        <button>Vaciar favoritos</button>
    </form>
@endsection
