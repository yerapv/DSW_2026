<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $t) {
            $t->id();
            $t->string('nombre');
            $t->unsignedInteger('precio_dia_centimos');
            $t->timestamps();
        });
        Schema::create('carritos', function (Blueprint $t) {
            $t->id();
            $t->timestamps();
        });
        Schema::create('lineas_carrito', function (Blueprint $t) {
            $t->id();
            $t->foreignId('carrito_id')->constrained('carritos')->cascadeOnDelete();
            $t->foreignId('producto_id')->constrained('productos');
            $t->unsignedInteger('dias');
            $t->unsignedInteger('precio_centimos');
            $t->timestamps();
        });
        Schema::create('presupuestos', function (Blueprint $t) {
            $t->id();
            $t->unsignedInteger('total_centimos');
            $t->json('instantanea');
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
        Schema::dropIfExists('lineas_carrito');
        Schema::dropIfExists('carritos');
        Schema::dropIfExists('productos');
    }
};
