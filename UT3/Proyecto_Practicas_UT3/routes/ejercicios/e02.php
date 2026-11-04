<?php

use App\Ejercicios\E02\Http\Controllers\PreferenciaController;
use Illuminate\Support\Facades\Route;

Route::get('/lectura', [PreferenciaController::class, 'show'])->name('lectura');
Route::post('/lectura/tema', [PreferenciaController::class, 'store'])->name('tema.store');
Route::delete('/lectura/tema', [PreferenciaController::class, 'destroy'])->name('tema.destroy');
