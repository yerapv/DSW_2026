<?php

use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/vehiculos', [VehiculoController::class, 'index']);
Route::get('/vehiculos/nuevo', [VehiculoController::class, 'create']);
Route::post('/vehiculos', [VehiculoController::class, 'store']);
