<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Una migración nueva actualiza también las bases de datos que ya tienen productos.
return new class extends Migration
{
    /** Nullable permite conservar los productos existentes hasta subirles una foto. */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('ruta_imagen')->nullable();
        });
    }

    /** Revertimos solo la columna incorporada en esta migración. */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('ruta_imagen');
        });
    }
};
