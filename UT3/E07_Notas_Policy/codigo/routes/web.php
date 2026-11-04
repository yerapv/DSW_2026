<?php

use App\Http\Controllers\NotaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/notas', [NotaController::class, 'index']);
    Route::post('/notas', [NotaController::class, 'store']);
    Route::get('/notas/{nota}', [NotaController::class, 'show']);
    Route::put('/notas/{nota}', [NotaController::class, 'update']);
    Route::delete('/notas/{nota}', [NotaController::class, 'destroy']);
});
