<?php

use App\Ejercicios\E18\Http\Controllers\AlquilerController;
use Illuminate\Support\Facades\Route;

Route::get('/alquiler', [AlquilerController::class, 'index'])->name('index');
Route::post('/alquiler/lineas', [AlquilerController::class, 'add'])->name('add');
Route::post('/alquiler/presupuesto', [AlquilerController::class, 'quote'])->name('quote');
Route::get('/alquiler/presupuestos/{presupuesto}', [AlquilerController::class, 'showQuote'])->name('showQuote');
