<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carritos', function (Blueprint $table) {
            // Los carritos anteriores no tenían propietario: no se inventa uno.
            // La aplicación exige cliente en todos los carritos nuevos.
            $table->foreignId('cliente_id')->nullable()->unique()->constrained('clientes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('carritos', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropUnique(['cliente_id']);
            $table->dropColumn('cliente_id');
        });
    }
};
