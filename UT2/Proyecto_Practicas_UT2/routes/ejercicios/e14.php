<?php

use App\Ejercicios\E14\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('index');
Route::get('/vehiculos/nuevo', [VehiculoController::class, 'create'])->name('create');
Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('store');
