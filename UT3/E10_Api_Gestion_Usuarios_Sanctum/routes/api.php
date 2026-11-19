<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

Route::post('registrar', [AuthController::class, 'registrar']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('perfil', [AuthController::class, 'perfil']);
    Route::post('logout', [AuthController::class, 'logout']);
});