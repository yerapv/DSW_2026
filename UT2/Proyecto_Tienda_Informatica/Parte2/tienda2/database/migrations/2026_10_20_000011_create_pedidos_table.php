<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Se mantiene un archivo por tabla y no se modifican los presupuestos existentes.
return new class extends Migration
{
    /** El histórico conserva importes y nombres aunque después cambie el catálogo. */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('total_centimos');
            $table->json('instantanea');
            $table->string('metodo_pago', 20);
            $table->string('estado', 30);
            $table->timestamps();
        });
    }

    /** Esta migración revierte únicamente su propia tabla. */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
