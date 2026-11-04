<?php

use App\Ejercicios\E01\Http\Controllers\ContadorController;
use Illuminate\Support\Facades\Route;

Route::get('/contador', [ContadorController::class, 'show'])->name('contador.show');
Route::post('/contador/reiniciar', [ContadorController::class, 'reset'])->name('contador.reset');
