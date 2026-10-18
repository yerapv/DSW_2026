<?php

use App\Http\Controllers\BienvenidaController;
use Illuminate\Support\Facades\Route;

Route::get('/bienvenida', [BienvenidaController::class, 'index']);
