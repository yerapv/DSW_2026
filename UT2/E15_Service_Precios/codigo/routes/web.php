<?php

use App\Http\Controllers\ImprentaController;
use Illuminate\Support\Facades\Route;

Route::get('/imprenta', [ImprentaController::class, 'create']);
Route::post('/imprenta', [ImprentaController::class, 'calculate']);
