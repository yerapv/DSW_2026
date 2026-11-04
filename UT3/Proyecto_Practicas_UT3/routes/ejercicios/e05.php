<?php

use App\Ejercicios\E05\Http\Controllers\AccesoController;
use Illuminate\Support\Facades\Route;

// middleware('guest') para rutas accesibles solo para usuarios no autenticados
Route::middleware('guest')->group(function () {
    Route::get('/acceso', [AccesoController::class, 'create'])->name('login');
    Route::post('/acceso', [AccesoController::class, 'store'])->name('login.store');
});

// middleware('auth') para rutas accesibles solo para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/perfil', fn () => view('ejercicios.e05.perfil'))->name('perfil');
    Route::post('/salir', [AccesoController::class, 'destroy'])->name('logout');
});
