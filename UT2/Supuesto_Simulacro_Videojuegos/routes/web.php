<?php

use App\Http\Controllers\VideojuegoController;
use Illuminate\Support\Facades\Route;

// Registra las rutas convencionales del recurso y sus nombres videojuegos.*.
// GET muestra páginas, POST crea, PUT/PATCH actualiza y DELETE elimina.
// Laravel dirige cada petición a la acción correspondiente del controlador.
Route::resource('videojuegos', VideojuegoController::class);
