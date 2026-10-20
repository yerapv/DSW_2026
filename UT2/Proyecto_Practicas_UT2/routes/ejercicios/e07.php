<?php

use App\Ejercicios\E07\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;

Route::get('/reserva', [ReservaController::class, 'create'])->name('create');
Route::post('/reserva', [ReservaController::class, 'store'])->name('store');
Route::get('/reserva/correcta', [ReservaController::class, 'correcta'])->name('correcta');
