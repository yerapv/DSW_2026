<?php

namespace App\Services;

use App\Enums\EstadoPedido;
use App\Models\Carrito;
use App\Models\MetodoPago;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ServicioPedidos
{
    /** Simula el pago, guarda el pedido y retira las líneas compradas del carrito. */
    public function pagar(Carrito $carrito, string $metodoPago): Pedido
    {
        $metodo = MetodoPago::where('codigo', $metodoPago)->where('activo', true)->first();
        if (! $metodo) {
            throw new RuntimeException('Selecciona un método de pago simulado válido.');
        }

        // Las dos escrituras deben completarse juntas: crear pedido y vaciar selección.
        return DB::transaction(function () use ($carrito, $metodo) {
            // El bloqueo serializa dos intentos simultáneos de pagar el mismo carrito.
            $carrito = Carrito::whereKey($carrito->id)->lockForUpdate()->firstOrFail();
            $cliente = $carrito->cliente;
            if (! $cliente) {
                throw new RuntimeException('El carrito necesita un cliente antes de pagar.');
            }
            $lineas = $carrito->lineas()->with('producto')->orderBy('id')->get();
            if ($lineas->isEmpty()) {
                throw new RuntimeException('El carrito está vacío. Añade productos antes de pagar.');
            }

            $instantanea = $lineas->map(fn ($linea) => [
                'producto' => $linea->producto->nombre,
                'cantidad' => $linea->cantidad,
                'precio_centimos' => $linea->precio_centimos,
            ])->all();

            // El importe se calcula en el servidor, nunca se toma de un campo del navegador.
            $pedido = Pedido::create([
                'cliente_id' => $cliente->id,
                'datos_cliente' => $cliente->only(['nombre', 'email', 'direccion']),
                'metodo_pago_id' => $metodo->id,
                'metodo_pago' => $metodo->codigo,
                'metodo_pago_nombre' => $metodo->nombre,
                'estado' => EstadoPedido::EnPreparacion,
                'total_centimos' => array_sum(array_column($instantanea, 'precio_centimos')),
                'instantanea' => $instantanea,
            ]);

            // Solo se retiran las líneas incluidas en esta compra, no otras añadidas después.
            $carrito->lineas()->whereIn('id', $lineas->pluck('id'))->delete();

            return $pedido;
        });
    }
}
