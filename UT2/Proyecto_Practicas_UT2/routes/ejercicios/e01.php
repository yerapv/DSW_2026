<?php

use App\Ejercicios\E01\Http\Controllers\BienvenidaController;
use Illuminate\Support\Facades\Route;

Route::get('/bienvenida', [BienvenidaController::class, 'index'])->name('index');
