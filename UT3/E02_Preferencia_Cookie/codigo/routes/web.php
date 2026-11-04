<?php

use App\Http\Controllers\PreferenciaController;
use Illuminate\Support\Facades\Route;

Route::get('/lectura', [PreferenciaController::class, 'show']);
Route::post('/lectura/tema', [PreferenciaController::class, 'store'])->name('tema.store');
Route::delete('/lectura/tema', [PreferenciaController::class, 'destroy'])->name('tema.destroy');
