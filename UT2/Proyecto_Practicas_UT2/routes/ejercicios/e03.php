<?php

use App\Ejercicios\E03\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/menu', [MenuController::class, 'index'])->name('index');
