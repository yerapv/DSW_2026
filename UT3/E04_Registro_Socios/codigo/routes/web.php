<?php

use App\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Route;

Route::get('/registro', [RegistroController::class, 'create'])->middleware('guest')->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->middleware('guest');
Route::get('/bienvenida', fn() => view('bienvenida'))->middleware('auth');
