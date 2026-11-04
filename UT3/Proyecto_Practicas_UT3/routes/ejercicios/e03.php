<?php

use App\Ejercicios\E03\Http\Controllers\RecetaController;
use Illuminate\Support\Facades\Route;

Route::get('/recetas', [RecetaController::class, 'index'])->name('recetas');
Route::post('/recetas/{id}/favorito', [RecetaController::class, 'add'])->name('favorito.add');
Route::delete('/recetas/{id}/favorito', [RecetaController::class, 'remove'])->name('favorito.remove');
Route::delete('/favoritos', [RecetaController::class, 'clear'])->name('favoritos.clear');
