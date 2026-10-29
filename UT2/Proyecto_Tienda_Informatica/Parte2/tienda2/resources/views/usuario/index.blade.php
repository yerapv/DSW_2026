@extends('layouts.usuario')
@section('back-button', '')
@section('title', 'Tu Espacio')
@section('content')
    <p class="eyebrow">TU ESPACIO EN {{ config('tienda.nombre') }}</p>
    <h1>{{ $cliente ? 'Tu Espacio : '.$cliente->nombre : 'Área de usuario' }}</h1>
    <p class="lead">Selecciona un cliente de muestra para preparar su carrito, consultar sus presupuestos y revisar sus compras. Puedes cambiar de cliente en cualquier momento.</p>
    @include('usuario.carrito._seleccionar-cliente')

    @if ($cliente)
        <section class="space-profile" aria-label="Cliente seleccionado">
            <div><h2>{{ $cliente->nombre }}</h2><p>{{ $cliente->email }}</p><p>{{ $cliente->direccion ?? 'Dirección sin indicar' }}</p></div>
            <a class="button" href="{{ route('productos.index', ['cliente_id' => $cliente->id]) }}">Explorar el catálogo</a>
        </section>
        <div class="grid two space-summary">
            <a class="card linked" href="{{ route('carrito.index', ['cliente_id' => $cliente->id]) }}"><h2>Carrito y presupuestos</h2><p>{{ $cliente->carrito?->lineas->sum('cantidad') ?? 0 }} unidades en el carrito</p><p>{{ $cliente->presupuestos_count }} presupuestos guardados</p><span>Preparar la compra →</span></a>
            <a class="card linked" href="{{ route('pedidos.index', ['cliente_id' => $cliente->id]) }}"><h2>Mis pedidos</h2><p class="space-count">{{ $cliente->pedidos_count }}</p><span>Consultar compras y estados →</span></a>
        </div>
    @else
        <div class="table-wrap space-clients"><table aria-label="Espacios de clientes">
            <thead><tr><th>Cliente</th><th>Correo</th><th>Acceso</th></tr></thead><tbody>
                @forelse ($clientes as $opcion)<tr><td>{{ $opcion->nombre }}</td><td>{{ $opcion->email }}</td><td><a class="button secondary small" href="{{ route('usuario.index', ['cliente_id' => $opcion->id]) }}">Abrir Tu Espacio</a></td></tr>
                @empty<tr><td colspan="3">Todavía no hay clientes. Puedes crearlos desde administración.</td></tr>@endforelse
            </tbody>
        </table></div>
    @endif

    @include('usuario._menu-espacio')
    <h2>Formularios y servicios</h2>
    <p class="lead">Consulta tarifas, calcula un montaje o prepara tus datos antes de comprar.</p>
    <div class="grid three">
        <a class="card linked" href="{{ route('envios', ['cliente_id' => request('cliente_id')]) }}"><h3>Tarifas de envío</h3><p>Compara un envío normal con uno urgente.</p><span>Consultar tarifas →</span></a>
        <a class="card linked" href="{{ route('montaje.create', ['cliente_id' => request('cliente_id')]) }}"><h3>Presupuesto de montaje</h3><p>Calcula equipos, instalación y urgencia.</p><span>Calcular montaje →</span></a>
        <a class="card linked" href="{{ route('financiacion.create', ['cliente_id' => request('cliente_id')]) }}"><h3>Simular financiación</h3><p>Revisa las condiciones de la solicitud de ejemplo.</p><span>Comprobar solicitud →</span></a>
        <a class="card linked" href="{{ route('fichas.create', ['cliente_id' => request('cliente_id')]) }}"><h3>Ficha de cliente</h3><p>Guarda o actualiza los datos del cliente seleccionado.</p><span>Guardar datos →</span></a>
    </div>
@endsection
