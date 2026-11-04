<?php
use App\Http\Controllers\ContadorController;
use Illuminate\Support\Facades\Route;
Route::get('/contador', [ContadorController::class, 'show']);
Route::post('/contador/reiniciar', [ContadorController::class, 'reset'])->name('contador.reset');
