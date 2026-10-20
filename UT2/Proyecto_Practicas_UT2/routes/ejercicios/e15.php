<?php

use App\Ejercicios\E15\Http\Controllers\ImprentaController;
use Illuminate\Support\Facades\Route;

Route::get('/imprenta', [ImprentaController::class, 'create'])->name('create');
Route::post('/imprenta', [ImprentaController::class, 'calculate'])->name('calculate');
