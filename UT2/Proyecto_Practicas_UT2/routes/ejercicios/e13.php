<?php

use App\Ejercicios\E13\Http\Controllers\TallerController;
use Illuminate\Support\Facades\Route;

Route::get('/talleres', [TallerController::class, 'index'])->name('index');
