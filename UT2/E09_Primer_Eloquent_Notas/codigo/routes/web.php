<?php

use App\Http\Controllers\NotaController;
use Illuminate\Support\Facades\Route;

Route::get('/notas', [NotaController::class, 'index']);
Route::get('/notas/nueva', [NotaController::class, 'create']);
Route::post('/notas', [NotaController::class, 'store']);
