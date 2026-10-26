<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Cada archivo crea y revierte únicamente su propia tabla.
return new class extends Migration
{
    /** Crea la estructura de productos; los registros se insertan desde los seeders. */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('nombre', 150);
            $table->string('marca', 120);
            $table->unsignedSmallInteger('anio');
            $table->boolean('disponible')->default(false);
            $table->unsignedInteger('precio_centimos');
            $table->string('tipo', 20);
            $table->json('caracteristicas')->nullable();
            $table->timestamps();
        });
    }

    /** Laravel revierte primero las migraciones posteriores para respetar las FK. */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
