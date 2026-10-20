<?php

use App\Ejercicios\E16\Http\Controllers\SolicitudResidenciaController;
use Illuminate\Support\Facades\Route;

Route::get('/residencia', [SolicitudResidenciaController::class, 'create'])->name('create');
Route::post('/residencia', [SolicitudResidenciaController::class, 'store'])->name('store');
