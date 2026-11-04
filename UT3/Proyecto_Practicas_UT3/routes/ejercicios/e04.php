<?php

use App\Ejercicios\E04\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Route;

Route::get('/registro', [RegistroController::class, 'create'])->middleware('guest')->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->middleware('guest')->name('registro.store');
Route::get('/bienvenida', fn () => view('ejercicios.e04.bienvenida'))->middleware('auth')->name('bienvenida');
