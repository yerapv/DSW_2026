<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pedidos')->whereIn('estado', ['pagado_simulado', 'enviado'])->update(['estado' => 'preparando']);
        DB::table('pedidos')->where('estado', 'entregado')->update(['estado' => 'completado']);
    }

    public function down(): void
    {
        // Los estados agrupados no se pueden distinguir de nuevo sin inventar datos.
    }
};
