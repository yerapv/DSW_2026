@extends('layouts.admin')
@section('title', 'Avisos')
@section('content')
    <div class="section-heading">
        <div><p class="eyebrow">AL DÍA</p><h1>Tablón de avisos</h1></div>
        <a class="button" href="{{ route('admin.avisos.create') }}">Nuevo aviso</a>
    </div>
    <div class="table-wrap">
        <table class="management-table" aria-label="Listado de avisos">
            <thead><tr><th scope="col">Título</th><th scope="col">Contenido</th><th scope="col">Fecha</th><th scope="col">Acciones</th></tr></thead>
            <tbody>
                @forelse ($avisos as $aviso)
                    <tr>
                        <th scope="row"><a href="{{ route('admin.avisos.show', $aviso) }}">{{ $aviso->titulo }}</a></th>
                        <td class="pre-line">{{ $aviso->contenido }}</td><td>{{ $aviso->created_at->format('d/m/Y H:i') }}</td>
                        <td><div class="row-actions">
                            <a class="button secondary small" href="{{ route('admin.avisos.show', $aviso) }}">Ver</a>
                            <a class="button secondary small" href="{{ route('admin.avisos.edit', $aviso) }}" aria-label="Editar {{ $aviso->titulo }}">Editar</a>
                            <form method="POST" action="{{ route('admin.avisos.destroy', $aviso) }}">@csrf @method('DELETE')<button class="small danger" aria-label="Eliminar {{ $aviso->titulo }}">Eliminar</button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="4">Todavía no hay avisos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $avisos->links('pagination::bootstrap-4') }}</div>
@endsection
