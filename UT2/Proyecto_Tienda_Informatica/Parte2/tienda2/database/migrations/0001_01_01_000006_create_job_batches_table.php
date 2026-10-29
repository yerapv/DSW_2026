<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Infraestructura Laravel: agrupaciones de trabajos.
// Cada archivo crea y revierte únicamente su propia tabla.
return new class extends Migration
{
    /** Crea la estructura de job_batches; los registros se insertan desde los seeders. */
    public function up(): void
    {
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });
    }

    /** Laravel revierte primero las migraciones posteriores para respetar las FK. */
    public function down(): void
    {
        Schema::dropIfExists('job_batches');
    }
};
