<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\MetodoPago;
use App\Services\ServicioPedidos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

// Se conserva create/store: GET presenta el formulario y POST procesa la simulación.
class PagoController extends Controller
{
    public function __construct(private readonly ServicioPedidos $pedidos) {}

    /** Presenta el resumen antes de confirmar el pago simulado. */
    public function create(Request $request): View|RedirectResponse
    {
        $datos = $request->validate(['cliente_id' => ['required', 'integer', 'exists:clientes,id']], [
            'cliente_id.required' => 'Selecciona el cliente del carrito antes de pagar.',
            'cliente_id.integer' => 'Selecciona un cliente válido para pagar.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ]);
        $carrito = Carrito::paraCliente(Cliente::findOrFail($datos['cliente_id']))->load('cliente', 'lineas.producto');
        if ($carrito->lineas->isEmpty()) {
            return redirect()->route('carrito.index', ['cliente_id' => $carrito->cliente_id])->withErrors(['carrito' => 'El carrito está vacío. Añade productos antes de pagar.']);
        }

        return view('usuario.pago.create', [
            'carrito' => $carrito,
            'totalCentimos' => $carrito->lineas->sum('precio_centimos'),
            'metodos' => MetodoPago::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    /** Valida la opción elegida y delega el guardado del pedido al Service. */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'metodo_pago' => ['required', 'string', Rule::exists('metodos_pago', 'codigo')->where('activo', true)],
        ], [
            // Mensajes propios del formulario, independientes del idioma global.
            'metodo_pago.required' => 'Selecciona un método para el pago simulado.',
            'metodo_pago.string' => 'Selecciona un método de pago válido.',
            'metodo_pago.exists' => 'Selecciona un método de pago activo.',
            'cliente_id.required' => 'Selecciona el cliente que realiza la compra.',
            'cliente_id.integer' => 'Selecciona un cliente válido para la compra.',
            'cliente_id.exists' => 'El cliente seleccionado para la compra no existe.',
        ]);
        $carrito = Carrito::paraCliente(Cliente::findOrFail($datos['cliente_id']));
        try {
            $pedido = $this->pedidos->pagar($carrito, $datos['metodo_pago']);
        } catch (RuntimeException $exception) {
            return redirect()->route('carrito.index', ['cliente_id' => $carrito->cliente_id])->withErrors(['carrito' => $exception->getMessage()]);
        }

        return redirect()->route('pedidos.show', ['pedido' => $pedido, 'cliente_id' => $pedido->cliente_id]);
    }
}
