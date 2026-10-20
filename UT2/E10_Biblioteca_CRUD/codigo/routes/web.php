<?php

use App\Http\Controllers\LibroController;
use Illuminate\Support\Facades\Route;

Route::resource('libros', LibroController::class);
