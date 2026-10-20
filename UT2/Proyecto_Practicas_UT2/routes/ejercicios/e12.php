<?php

use App\Ejercicios\E12\Http\Controllers\AlumnoActividadController;
use Illuminate\Support\Facades\Route;

Route::get('/alumnos/{alumno}', [AlumnoActividadController::class, 'show'])->name('show');
Route::post('/alumnos/{alumno}/actividades', [AlumnoActividadController::class, 'attach'])->name('attach');
Route::delete('/alumnos/{alumno}/actividades/{actividad}', [AlumnoActividadController::class, 'detach'])->name('detach');
