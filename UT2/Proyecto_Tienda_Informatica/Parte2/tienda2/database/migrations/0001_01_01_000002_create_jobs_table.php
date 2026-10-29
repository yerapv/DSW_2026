<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Infraestructura Laravel: trabajos pendientes de la cola.
// Cada archivo crea y revierte únicamente su propia tabla.
return new class extends Migration
{
    /** Crea la estructura de jobs; los registros se insertan desde los seeders. */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedSmallInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    /** Laravel revierte primero las migraciones posteriores para respetar las FK. */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
