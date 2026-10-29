<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Infraestructura Laravel: tokens de recuperación de contraseña.
// Cada archivo crea y revierte únicamente su propia tabla.
return new class extends Migration
{
    /** Crea la estructura de password_reset_tokens; los registros se insertan desde los seeders. */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /** Laravel revierte primero las migraciones posteriores para respetar las FK. */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
