<?php

namespace App\Services;

use App\Models\Carrito;
use App\Models\Presupuesto;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ServicioPresupuestos
{
    /** Convierte la selección actual en un documento con precios históricos. */
    public function generar(Carrito $carrito): Presupuesto
    {
        return DB::transaction(function () use ($carrito) {
            $carrito = Carrito::whereKey($carrito->id)->lockForUpdate()->firstOrFail();
            $carrito->load('cliente', 'lineas.producto');
            if (! $carrito->cliente) {
                throw new RuntimeException('El carrito necesita un cliente antes de generar un presupuesto.');
            }
            if ($carrito->lineas->isEmpty()) {
                throw new RuntimeException('El carrito está vacío.');
            }
            // Se copian valores, no referencias vivas al nombre o precio actual.
            $snapshot = $carrito->lineas->map(fn ($linea) => [
                'producto_id' => $linea->producto_id,
                'producto' => $linea->producto->nombre,
                'cantidad' => $linea->cantidad,
                'precio_centimos' => $linea->precio_centimos,
            ])->all();

            // precio_centimos en cada línea es su importe completo, no la unidad.
            $presupuesto = Presupuesto::create([
                'cliente_id' => $carrito->cliente_id,
                'datos_cliente' => $carrito->cliente->only(['nombre', 'email', 'direccion']),
                'total_centimos' => $carrito->lineas->sum('precio_centimos'),
                'instantanea' => $snapshot,
            ]);

            $presupuesto->lineas()->createMany($carrito->lineas->map(fn ($linea) => [
                'producto_id' => $linea->producto_id,
                'producto_nombre' => $linea->producto->nombre,
                'cantidad' => $linea->cantidad,
                'precio_centimos' => $linea->precio_centimos,
            ])->all());

            return $presupuesto;
        });
    }
}
