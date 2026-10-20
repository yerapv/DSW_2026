<?php

use App\Ejercicios\E17\Http\Controllers\PiezaController;
use Illuminate\Support\Facades\Route;

Route::get('/piezas', [PiezaController::class, 'index'])->name('index');
Route::get('/piezas/nueva', [PiezaController::class, 'create'])->name('create');
Route::post('/piezas', [PiezaController::class, 'store'])->name('store');
Route::delete('/piezas/{pieza}', [PiezaController::class, 'destroy'])->name('destroy');
