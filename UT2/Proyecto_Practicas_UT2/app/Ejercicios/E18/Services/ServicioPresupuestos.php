<?php

namespace App\Ejercicios\E18\Services;

use App\Ejercicios\E18\Models\Carrito;
use App\Ejercicios\E18\Models\Presupuesto;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ServicioPresupuestos
{
    public function generar(Carrito $carrito): Presupuesto
    {
        $carrito->load('lineas.producto');
        if ($carrito->lineas->isEmpty()) {
            throw new RuntimeException('El carrito está vacío.');
        }

        return DB::transaction(function () use ($carrito) {
            $snapshot = $carrito->lineas->map(fn ($l) => ['producto' => $l->producto->nombre, 'dias' => $l->dias, 'precio_centimos' => $l->precio_centimos])->all();

            return Presupuesto::create(['total_centimos' => $carrito->lineas->sum('precio_centimos'), 'instantanea' => $snapshot]);
        });
    }
}
