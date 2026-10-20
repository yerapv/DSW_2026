<?php

use App\Ejercicios\E08\Http\Controllers\MascotaController;
use Illuminate\Support\Facades\Route;

Route::get('/mascotas/nueva', [MascotaController::class, 'create'])->name('create');
Route::post('/mascotas', [MascotaController::class, 'store'])->name('store');
