<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Cada archivo crea y revierte únicamente su propia tabla.
return new class extends Migration
{
    /** Crea la estructura de presupuestos; los registros se insertan desde los seeders. */
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('total_centimos');
            // La instantánea permanece aunque más tarde cambie el catálogo.
            $table->json('instantanea');
            $table->timestamps();
        });
    }

    /** Laravel revierte primero las migraciones posteriores para respetar las FK. */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
