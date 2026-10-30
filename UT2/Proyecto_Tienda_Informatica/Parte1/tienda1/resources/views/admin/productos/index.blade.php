@extends('layouts.admin')
@section('title', 'Productos')
@section('content')
<div class="section-heading">
    <div>
        <p class="eyebrow">ADMINISTRACIÓN DEL CATÁLOGO</p>
        <h1>Gestionar productos</h1>
    </div>
    <a class="button" href="{{ route('admin.productos.create') }}">Nuevo producto</a>
</div>
<form method="GET" action="{{ route('admin.productos.index') }}" class="filters">
    <label>Buscar por nombre<input name="q" value="{{ request('q') }}" placeholder="Teclado, portátil..."></label>
    <label>Tipo<select name="tipo">
            <option value="">Todos los tipos</option>
            <option value="portatil" @selected(request('tipo')==='portatil' )>Portátil</option>
            <option value="periferico" @selected(request('tipo')==='periferico' )>Periférico</option>
            <option value="componente" @selected(request('tipo')==='componente' )>Componente</option>
        </select></label>
    <button>Buscar</button><a href="{{ route('admin.productos.index') }}">Limpiar</a>
</form>
<p class="muted">{{ $productos->total() }} productos encontrados</p>
<div class="table-wrap">
    <table class="management-table" aria-label="Listado de productos">
        <thead>
            <tr>
                <th scope="col">Fotografía</th>
                <th scope="col">Producto</th>
                <th scope="col">Marca</th>
                <th scope="col">Año</th>
                <th scope="col">Categoría</th>
                <th scope="col">Tipo</th>
                <th scope="col">Precio</th>
                <th scope="col">Disponibilidad</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
            <tr>
                <td>@include('partials.foto-producto', ['clase' => 'admin-thumbnail'])</td>
                <th scope="row"><a href="{{ route('admin.productos.show', $producto) }}">{{ $producto->nombre }}</a></th>
                <td>{{ $producto->marca }}</td>
                <td>{{ $producto->anio }}</td>
                <td>{{ $producto->categoria->nombre }}</td>
                <td>{{ $producto->tipo->value }}</td>
                <td class="amount">{{ number_format($producto->precio_centimos / 100, 2, ',', '.') }} €</td>
                <td>{{ $producto->disponible ? 'Disponible' : 'No disponible' }}</td>
                <td>
                    <div class="row-actions">
                        <a class="button secondary small" href="{{ route('admin.productos.show', $producto) }}">Ver</a>
                        <a class="button secondary small" href="{{ route('admin.productos.edit', $producto) }}" aria-label="Editar {{ $producto->nombre }}">Editar</a>
                        <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}">@csrf @method('DELETE')<button class="small danger" aria-label="Eliminar {{ $producto->nombre }}">Eliminar</button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">No se han encontrado productos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $productos->links('pagination::simple-bootstrap-4') }}</div>
@endsection
