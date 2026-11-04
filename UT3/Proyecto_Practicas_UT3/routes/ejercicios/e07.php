<?php

use App\Ejercicios\E07\Http\Controllers\NotaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
    Route::post('/notas', [NotaController::class, 'store'])->name('notas.store');
    Route::get('/notas/{nota}', [NotaController::class, 'show'])->name('notas.show');
    Route::put('/notas/{nota}', [NotaController::class, 'update'])->name('notas.update');
    Route::delete('/notas/{nota}', [NotaController::class, 'destroy'])->name('notas.destroy');
});
