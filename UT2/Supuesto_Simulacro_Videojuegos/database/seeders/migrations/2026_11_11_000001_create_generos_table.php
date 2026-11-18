<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Se ejecuta con migrate para crear la tabla antes de la de videojuegos.
    public function up(): void
    {
        Schema::create('generos', function (Blueprint $table) {
            // Clave primaria autoincremental.
            $table->id();
            $table->string('nombre', 40);
            // Crea created_at y updated_at, que Eloquent gestiona automáticamente.
            $table->timestamps();
        });
    }

    // Deshace esta migración al ejecutar migrate:rollback.
    public function down(): void
    {
        Schema::dropIfExists('generos');
    }
};
