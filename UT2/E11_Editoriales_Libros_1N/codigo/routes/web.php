<?php

use App\Http\Controllers\EditorialController;
use App\Http\Controllers\LibroEditorialController;
use Illuminate\Support\Facades\Route;

Route::get('/editoriales', [EditorialController::class, 'index']);
Route::get('/editoriales/libros/nuevo', [LibroEditorialController::class, 'create']);
Route::post('/editoriales/libros', [LibroEditorialController::class, 'store']);
