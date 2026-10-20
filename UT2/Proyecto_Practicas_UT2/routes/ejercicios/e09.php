<?php

use App\Ejercicios\E09\Http\Controllers\NotaController;
use Illuminate\Support\Facades\Route;

Route::get('/notas', [NotaController::class, 'index'])->name('index');
Route::get('/notas/nueva', [NotaController::class, 'create'])->name('create');
Route::post('/notas', [NotaController::class, 'store'])->name('store');
