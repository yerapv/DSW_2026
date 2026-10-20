<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'resumen', ['ejercicios' => config('ejercicios')])->name('resumen');

foreach (array_keys(config('ejercicios')) as $ejercicio) {
    Route::prefix($ejercicio)->name($ejercicio.'.')->group(__DIR__.'/ejercicios/'.$ejercicio.'.php');
}
