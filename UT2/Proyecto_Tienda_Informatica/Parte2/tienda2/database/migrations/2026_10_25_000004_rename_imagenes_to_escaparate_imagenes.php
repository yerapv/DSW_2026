<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('imagenes', 'escaparate_imagenes');
    }

    public function down(): void
    {
        Schema::rename('escaparate_imagenes', 'imagenes');
    }
};
