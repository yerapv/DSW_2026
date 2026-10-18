<?php

use App\Http\Controllers\InscripcionController;
use Illuminate\Support\Facades\Route;

Route::get('/inscripcion', [InscripcionController::class, 'create']);
Route::post('/inscripcion', [InscripcionController::class, 'store']);
