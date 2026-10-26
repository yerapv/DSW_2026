<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

// Toda la aplicación se limita a la administración, con acceso directo.
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('productos', ProductoController::class)->parameters(['productos' => 'producto']);

    // Se conservan las direcciones de alta; el resto del CRUD usa rutas de recurso.
    Route::get('/avisos/nuevo', [AvisoController::class, 'create'])->name('avisos.create');
    Route::resource('avisos', AvisoController::class)->except('create')->parameters(['avisos' => 'aviso']);

    Route::get('/imagenes/nueva', [ImagenController::class, 'create'])->name('imagenes.create');
    Route::resource('imagenes', ImagenController::class)->except('create')->parameters(['imagenes' => 'imagen']);
});
