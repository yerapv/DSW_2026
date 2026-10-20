<?php

use App\Ejercicios\E02\Http\Controllers\PeliculaController;
use Illuminate\Support\Facades\Route;

Route::get('/pelicula', [PeliculaController::class, 'show'])->name('show');
