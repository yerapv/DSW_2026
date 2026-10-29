<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $repetidos = DB::table('lineas_carrito')
                ->select('carrito_id', 'producto_id')
                ->selectRaw('MIN(id) as id, SUM(cantidad) as cantidad, SUM(precio_centimos) as importe')
                ->groupBy('carrito_id', 'producto_id')->havingRaw('COUNT(*) > 1')->get();
            foreach ($repetidos as $grupo) {
                DB::table('lineas_carrito')->where('id', $grupo->id)->update([
                    'cantidad' => $grupo->cantidad, 'precio_centimos' => $grupo->importe,
                ]);
                DB::table('lineas_carrito')->where('carrito_id', $grupo->carrito_id)
                    ->where('producto_id', $grupo->producto_id)->where('id', '<>', $grupo->id)->delete();
            }
        });
        Schema::table('lineas_carrito', function (Blueprint $table) {
            $table->unique(['carrito_id', 'producto_id']);
        });
    }

    public function down(): void
    {
        // MySQL puede haber sustituido el índice de la FK por el índice compuesto.
        if (! Schema::hasIndex('lineas_carrito', ['carrito_id'])) {
            Schema::table('lineas_carrito', function (Blueprint $table) {
                $table->index('carrito_id');
            });
        }
        Schema::table('lineas_carrito', function (Blueprint $table) {
            $table->dropUnique(['carrito_id', 'producto_id']);
        });
    }
};
