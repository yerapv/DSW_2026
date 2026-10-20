<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Alumno;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumnoActividadController extends Controller
{
    public function show(Alumno $alumno): View
    {
        $alumno->load('actividades');
        return view('alumnos.show', ['alumno' => $alumno, 'actividades' => Actividad::orderBy('nombre')->get()]);
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
