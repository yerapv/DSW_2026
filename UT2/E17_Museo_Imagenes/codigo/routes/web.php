<?php

use App\Http\Controllers\PiezaController;
use Illuminate\Support\Facades\Route;

Route::get('/piezas', [PiezaController::class, 'index']);
Route::get('/piezas/nueva', [PiezaController::class, 'create']);
Route::post('/piezas', [PiezaController::class, 'store']);
Route::delete('/piezas/{pieza}', [PiezaController::class, 'destroy']);
