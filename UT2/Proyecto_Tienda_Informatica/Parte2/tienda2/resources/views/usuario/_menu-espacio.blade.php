{{-- Este parcial se incluye solo en usuario/index, al acceder con el botón Usuario. --}}
<section class="user-space-menu" aria-label="Tu espacio">
    <p class="eyebrow">TU ESPACIO</p>
    <nav aria-label="Servicios de usuario">
        <a href="{{ route('usuario.index', ['cliente_id' => request('cliente_id')]) }}">Área de usuario</a>
        <a href="{{ route('envios', ['cliente_id' => request('cliente_id')]) }}">Tarifas de envío</a>
        <a href="{{ route('montaje.create', ['cliente_id' => request('cliente_id')]) }}">Calcular montaje</a>
        <a href="{{ route('fichas.create', ['cliente_id' => request('cliente_id')]) }}">Preparar ficha de cliente</a>
        <a href="{{ route('financiacion.create', ['cliente_id' => request('cliente_id')]) }}">Simular financiación</a>
        <a href="{{ route('carrito.index', ['cliente_id' => request('cliente_id')]) }}">Carrito y presupuesto</a>
        <a href="{{ route('pedidos.index', ['cliente_id' => request('cliente_id')]) }}">Histórico de pedidos</a>
    </nav>
</section>
