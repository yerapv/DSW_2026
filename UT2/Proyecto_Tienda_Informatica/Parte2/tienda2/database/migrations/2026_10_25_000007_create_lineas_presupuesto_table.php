<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lineas_presupuesto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->string('producto_nombre', 150);
            $table->unsignedInteger('cantidad');
            $table->unsignedInteger('precio_centimos');
            $table->timestamps();
        });

        // Los JSON antiguos no incluían el id del producto: se conservan sus datos
        // sin atribuirlos a un artículo que solo coincida por el nombre.
        DB::table('presupuestos')->orderBy('id')->chunkById(100, function ($presupuestos) {
            foreach ($presupuestos as $presupuesto) {
                foreach (json_decode($presupuesto->instantanea, true) ?? [] as $linea) {
                    $productoId = $linea['producto_id'] ?? null;
                    if ($productoId !== null && ! DB::table('productos')->where('id', $productoId)->exists()) {
                        $productoId = null;
                    }
                    DB::table('lineas_presupuesto')->insert([
                        'presupuesto_id' => $presupuesto->id,
                        'producto_id' => $productoId,
                        'producto_nombre' => $linea['producto'],
                        'cantidad' => $linea['cantidad'],
                        'precio_centimos' => $linea['precio_centimos'],
                        'created_at' => $presupuesto->created_at,
                        'updated_at' => $presupuesto->updated_at,
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lineas_presupuesto');
    }
};
