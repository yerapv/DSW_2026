<?php

use App\Http\Controllers\SolicitudResidenciaController;
use Illuminate\Support\Facades\Route;

Route::get('/residencia', [SolicitudResidenciaController::class, 'create']);
Route::post('/residencia', [SolicitudResidenciaController::class, 'store']);
