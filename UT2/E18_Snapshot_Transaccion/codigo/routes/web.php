<?php

use App\Http\Controllers\AlquilerController;
use Illuminate\Support\Facades\Route;

Route::get('/alquiler', [AlquilerController::class, 'index']);
Route::post('/alquiler/lineas', [AlquilerController::class, 'add']);
Route::post('/alquiler/presupuesto', [AlquilerController::class, 'quote']);
Route::get('/alquiler/presupuestos/{presupuesto}', [AlquilerController::class, 'showQuote']);
