<?php

use App\Http\Controllers\PeliculaController;
use Illuminate\Support\Facades\Route;

Route::get('/pelicula', [PeliculaController::class, 'show']);
