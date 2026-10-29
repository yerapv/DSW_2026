@extends('layouts.usuario')
@section('volver-url', route('usuario.index', ['cliente_id' => request('cliente_id')]))
@section('title', 'Ficha de cliente')
@section('content')
    <p class="eyebrow">CONÓCENOS</p><h1>Ficha de cliente</h1>
    <p class="lead">Guarda tus datos para utilizarlos en la tienda. Si has seleccionado un cliente, actualizarás su ficha.</p>
    <form method="POST" action="{{ route('fichas.store', ['cliente_id' => request('cliente_id')]) }}" class="card form">
        @csrf
        <label>Nombre<input name="nombre" value="{{ old('nombre', $cliente?->nombre) }}" maxlength="80" required></label>
        {{-- Las opciones proceden de config/tienda.php a través del Controller. --}}
        <label>Tipo<select name="tipo">@foreach ($tipos as $tipo)<option value="{{ $tipo }}" @selected(old('tipo', $cliente?->tipo ?? 'particular') === $tipo)>{{ ucfirst($tipo) }}</option>@endforeach</select></label>
        <label>Edad<input type="number" name="edad" min="0" max="120" value="{{ old('edad', $cliente?->edad) }}" required></label>
        <label>Email<input type="email" name="email" value="{{ old('email', $cliente?->email) }}" required></label>
        <label>Dirección<input name="direccion" maxlength="255" value="{{ old('direccion', $cliente?->direccion) }}"></label>
        <button>Guardar ficha</button>
    </form>
@endsection
