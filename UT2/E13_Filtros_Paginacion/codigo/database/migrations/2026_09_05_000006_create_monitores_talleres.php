<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monitores', function (Blueprint $t) {
            $t->id();
            $t->string('nombre');
            $t->timestamps();
        });
        Schema::create('talleres', function (Blueprint $t) {
            $t->id();
            $t->foreignId('monitor_id')->constrained('monitores');
            $t->string('titulo');
            $t->string('nivel', 20);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('talleres');
        Schema::dropIfExists('monitores');
    }
};
