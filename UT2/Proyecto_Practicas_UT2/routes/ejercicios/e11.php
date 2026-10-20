<?php

use App\Ejercicios\E11\Http\Controllers\EditorialController;
use App\Ejercicios\E11\Http\Controllers\LibroEditorialController;
use Illuminate\Support\Facades\Route;

Route::get('/editoriales', [EditorialController::class, 'index'])->name('index');
Route::get('/editoriales/libros/nuevo', [LibroEditorialController::class, 'create'])->name('create');
Route::post('/editoriales/libros', [LibroEditorialController::class, 'store'])->name('store');
