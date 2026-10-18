<?php

use App\Http\Controllers\EnvioController;
use Illuminate\Support\Facades\Route;

Route::get('/envios', [EnvioController::class, 'index']);
