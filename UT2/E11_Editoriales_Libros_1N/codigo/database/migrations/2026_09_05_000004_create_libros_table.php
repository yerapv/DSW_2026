<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $t) {
            $t->id();
            $t->foreignId('editorial_id')->constrained('editoriales')->cascadeOnUpdate()->restrictOnDelete();
            $t->string('titulo');
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
