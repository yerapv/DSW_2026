<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Se añade la columna 'rol' a la tabla 'users' con un valor por defecto de 'socio'.
        Schema::table('users', fn(Blueprint $t) => $t->string('rol')->default('socio'));
    }
    public function down(): void
    {
        // Se elimina la columna 'rol' de la tabla 'users'.
        Schema::table('users', fn(Blueprint $t) => $t->dropColumn('rol'));
    }
};
