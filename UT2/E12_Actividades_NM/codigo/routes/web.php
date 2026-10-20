<?php

use App\Http\Controllers\AlumnoActividadController;
use Illuminate\Support\Facades\Route;

Route::get('/alumnos/{alumno}', [AlumnoActividadController::class, 'show']);
Route::post('/alumnos/{alumno}/actividades', [AlumnoActividadController::class, 'attach']);
Route::delete('/alumnos/{alumno}/actividades/{actividad}', [AlumnoActividadController::class, 'detach']);
