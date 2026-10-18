<?php

use App\Http\Controllers\MascotaController;
use Illuminate\Support\Facades\Route;

Route::get('/mascotas/nueva', [MascotaController::class, 'create']);
Route::post('/mascotas', [MascotaController::class, 'store']);
