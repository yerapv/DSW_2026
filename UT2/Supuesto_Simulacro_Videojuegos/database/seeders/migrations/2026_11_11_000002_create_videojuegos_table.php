<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Define el esquema de videojuegos; generos debe existir antes para crear la FK.
    public function up(): void
    {
        Schema::create('videojuegos', function (Blueprint $table) {
            $table->id();
            // Relación 1:N: genero_id referencia a generos.id.
            // restrictOnDelete impide borrar un género que todavía tiene videojuegos.
            $table->foreignId('genero_id')->constrained('generos')->restrictOnDelete();
            $table->string('titulo', 100);
            // Ocho cifras en total y dos decimales: permite precios hasta 999999.99.
            $table->decimal('precio', 8, 2);
            // Guarda una ruta como videojuegos/nombre-generado.jpg.
            $table->string('imagen');
            $table->timestamps();
        });
    }

    // Al revertir las migraciones, esta tabla se elimina antes que generos.
    public function down(): void
    {
        Schema::dropIfExists('videojuegos');
    }
};
