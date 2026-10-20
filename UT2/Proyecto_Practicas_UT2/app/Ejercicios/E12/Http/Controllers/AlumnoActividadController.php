<?php

namespace App\Ejercicios\E12\Http\Controllers;

use App\Ejercicios\E12\Models\Actividad;
use App\Ejercicios\E12\Models\Alumno;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumnoActividadController extends Controller
{
    public function show(Alumno $alumno): View
    {
        $alumno->load('actividades');

        return view('ejercicios.e12.alumnos.show', ['alumno' => $alumno, 'actividades' => Actividad::orderBy('nombre')->get()]);
    }

    public function attach(Request $r, Alumno $alumno): RedirectResponse
    {
        $d = $r->validate(['actividad_id' => ['required', 'exists:actividades,id']], [
            'actividad_id.required' => 'Selecciona la actividad en la que deseas inscribir al alumno.',
        ]);
        $alumno->actividades()->syncWithoutDetaching([$d['actividad_id'] => ['fecha_inscripcion' => now()->toDateString()]]);

        return back();
    }

    public function detach(Alumno $alumno, Actividad $actividad): RedirectResponse
    {
        $alumno->actividades()->detach($actividad->id);

        return back();
    }
}
