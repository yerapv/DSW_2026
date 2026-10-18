<?php

use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;

Route::get('/reserva', [ReservaController::class, 'create']);
Route::post('/reserva', [ReservaController::class, 'store']);
Route::get('/reserva/correcta', [ReservaController::class, 'correcta']);
