<?php

use App\Ejercicios\E06\Http\Controllers\InscripcionController;
use Illuminate\Support\Facades\Route;

Route::get('/inscripcion', [InscripcionController::class, 'create'])->name('create');
Route::post('/inscripcion', [InscripcionController::class, 'store'])->name('store');
