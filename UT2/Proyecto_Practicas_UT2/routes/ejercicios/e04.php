<?php

use App\Ejercicios\E04\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;

Route::get('/eventos', [EventoController::class, 'index'])->name('index');
