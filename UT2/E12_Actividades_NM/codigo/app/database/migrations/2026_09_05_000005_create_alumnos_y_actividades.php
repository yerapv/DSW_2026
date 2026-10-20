<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $t) {
            $t->id();
            $t->string('nombre');
            $t->timestamps();
        });
        Schema::create('actividades', function (Blueprint $t) {
            $t->id();
            $t->string('nombre');
            $t->timestamps();
        });
        Schema::create('actividad_alumno', function (Blueprint $t) {
            $t->id();
            $t->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $t->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();
            $t->date('fecha_inscripcion');
            $t->unique(['alumno_id', 'actividad_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('actividad_alumno');
        Schema::dropIfExists('actividades');
        Schema::dropIfExists('alumnos');
    }
};
