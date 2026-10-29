<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Cada archivo crea y revierte únicamente su propia tabla.
return new class extends Migration
{
    /** Crea la estructura de clientes; los registros se insertan desde los seeders. */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->timestamps();
        });
    }

    /** Laravel revierte primero las migraciones posteriores para respetar las FK. */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
