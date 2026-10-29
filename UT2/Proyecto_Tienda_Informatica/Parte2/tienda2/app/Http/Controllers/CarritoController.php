<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\Presupuesto;
use App\Models\Producto;
use App\Services\ServicioPresupuestos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class CarritoController extends Controller
{
    /** El Service concentra la generación del documento histórico. */
    public function __construct(private readonly ServicioPresupuestos $presupuestos) {}

    /** Carga todas las líneas y sus productos antes de construir la vista. */
    public function index(Request $request): View
    {
        $datos = $request->validate(['cliente_id' => ['nullable', 'integer', 'exists:clientes,id']], $this->mensajesCliente());
        $cliente = isset($datos['cliente_id']) ? Cliente::findOrFail($datos['cliente_id']) : null;
        $carrito = $cliente ? Carrito::paraCliente($cliente)->load('lineas.producto') : null;

        return view('usuario.carrito.index', [
            'clientes' => Cliente::orderBy('nombre')->get(),
            'cliente' => $cliente,
            'productos' => Producto::all(),
            'carrito' => $carrito,
            'totalCentimos' => $carrito?->lineas->sum('precio_centimos') ?? 0,
            'presupuestos' => $cliente?->presupuestos()->orderByDesc('id')->paginate(5)->withQueryString(),
        ]);
    }

    /** Calcula el importe en el servidor, como precio_dia * dias en el alquiler. */
    public function add(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'producto_id' => ['bail', 'required', 'integer', 'exists:productos,id'],
            'cantidad' => ['required', 'integer', 'between:1,100'],
        ], $this->mensajesCliente() + [
            // Mensajes propios del formulario, independientes del idioma global.
            'producto_id.required' => 'Selecciona un producto para añadirlo al carrito.',
            'producto_id.integer' => 'Selecciona un único producto válido.',
            'producto_id.exists' => 'El producto que quieres añadir al carrito no existe.',
            'cantidad.required' => 'Indica cuántas unidades quieres añadir al carrito.',
            'cantidad.integer' => 'La cantidad de unidades del carrito debe ser un número entero.',
            'cantidad.between' => 'Puedes añadir entre :min y :max unidades en cada envío.',
        ]);
        $cliente = Cliente::findOrFail($datos['cliente_id']);
        $carrito = Carrito::paraCliente($cliente);
        $producto = Producto::findOrFail($datos['producto_id']);
        DB::transaction(function () use ($carrito, $producto, $datos) {
            // El mismo bloqueo que utiliza el pago evita perder sumas simultáneas.
            $carrito = Carrito::whereKey($carrito->id)->lockForUpdate()->firstOrFail();
            $importeAñadido = $producto->precio_centimos * (int) $datos['cantidad'];
            if ((int) $carrito->lineas()->sum('precio_centimos') + $importeAñadido > 4294967295) {
                throw ValidationException::withMessages(['cantidad' => 'El importe total del carrito supera el máximo permitido.']);
            }
            $linea = $carrito->lineas()->firstOrNew(['producto_id' => $producto->id]);
            $linea->cantidad = ($linea->cantidad ?? 0) + (int) $datos['cantidad'];
            $linea->precio_centimos = ($linea->precio_centimos ?? 0) + $producto->precio_centimos * (int) $datos['cantidad'];
            $linea->save();
        });

        return redirect()->route('carrito.index', ['cliente_id' => $cliente->id]);
    }

    /** Generar el presupuesto no altera la selección ni realiza un cobro. */
    public function quote(Request $request): RedirectResponse
    {
        $datos = $request->validate(['cliente_id' => ['required', 'integer', 'exists:clientes,id']], $this->mensajesCliente());
        $carrito = Carrito::paraCliente(Cliente::findOrFail($datos['cliente_id']));
        // La excepción de negocio se transforma en un mensaje legible del formulario.
        try {
            $presupuesto = $this->presupuestos->generar($carrito);
        } catch (RuntimeException $exception) {
            return redirect()->route('carrito.index', ['cliente_id' => $carrito->cliente_id])->withErrors(['carrito' => $exception->getMessage()]);
        }

        return redirect()->route('carrito.presupuestos.show', ['presupuesto' => $presupuesto, 'cliente_id' => $carrito->cliente_id]);
    }

    /** La vista histórica lee las líneas guardadas, sin recalcular los precios del catálogo. */
    public function showQuote(Presupuesto $presupuesto): View
    {
        $presupuesto->load('lineas');

        return view('usuario.carrito.quote', compact('presupuesto'));
    }

    private function mensajesCliente(): array
    {
        return [
            'cliente_id.required' => 'Selecciona el cliente del carrito.',
            'cliente_id.integer' => 'Selecciona un cliente válido para el carrito.',
            'cliente_id.exists' => 'El cliente del carrito no existe.',
        ];
    }
}
