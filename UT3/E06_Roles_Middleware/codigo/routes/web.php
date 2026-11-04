<?php

use Illuminate\Support\Facades\Route;

// Comprueba con 2 middleware:
// auth (autenticación): comprueba que el usuario ha iniciado sesión. Si no está autenticado, lo redirige a la página de login (en una petición web habitual).
// gestor (autorización): comprueba que el usuario autenticado tiene el rol de gestor. Si no cumple esta condición, normalmente se devuelve un error HTTP 403.

Route::middleware(['auth', 'gestor'])->group(function () {

    Route::view('/gestion', 'gestion');
});
