<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['pendiente' => 1, 'preparando' => 2, 'completado' => 3, 'cancelado' => 4] as $texto => $numero) {
            DB::table('pedidos')->where('estado', $texto)->update(['estado' => $numero]);
        }
        foreach (['portatil' => 1, 'periferico' => 2, 'componente' => 3] as $texto => $numero) {
            DB::table('productos')->where('tipo', $texto)->update(['tipo' => $numero]);
        }
        Schema::table('pedidos', function (Blueprint $table) {
            $table->unsignedTinyInteger('estado')->change();
        });
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedTinyInteger('tipo')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('estado', 30)->change();
        });
        Schema::table('productos', function (Blueprint $table) {
            $table->string('tipo', 20)->change();
        });
        foreach ([1 => 'pendiente', 2 => 'preparando', 3 => 'completado', 4 => 'cancelado'] as $numero => $texto) {
            DB::table('pedidos')->where('estado', (string) $numero)->update(['estado' => $texto]);
        }
        foreach ([1 => 'portatil', 2 => 'periferico', 3 => 'componente'] as $numero => $texto) {
            DB::table('productos')->where('tipo', (string) $numero)->update(['tipo' => $texto]);
        }
    }
};
