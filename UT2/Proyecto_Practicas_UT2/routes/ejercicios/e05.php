<?php

use App\Ejercicios\E05\Http\Controllers\EnvioController;
use Illuminate\Support\Facades\Route;

Route::get('/envios', [EnvioController::class, 'index'])->name('index');
