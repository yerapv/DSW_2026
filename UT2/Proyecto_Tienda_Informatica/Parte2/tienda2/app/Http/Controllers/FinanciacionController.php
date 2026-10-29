<?php

namespace App\Http\Controllers;

use App\Services\ValidadorSolicitudFinanciacion;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FinanciacionController extends Controller
{
    /** Se inyecta una clase concreta, sin interfaces ni patrones adicionales. */
    public function __construct(private readonly ValidadorSolicitudFinanciacion $validador) {}

    /** Presenta el formulario de reglas de negocio ficticias. */
    public function create(): View
    {
        return view('usuario.financiacion.form');
    }

    /** Primero se valida la forma de los datos y después su aceptación de negocio. */
    public function store(Request $request): View
    {
        $datos = $request->validate([
            'edad' => ['required', 'integer', 'between:0,120'],
            'importe' => ['bail', 'required', 'numeric', 'min:0', 'regex:/\A[0-9]+(?:\.[0-9]{1,2})?\z/'],
            'antiguedad_meses' => ['required', 'integer', 'between:0,1440'],
            'documentacion_completa' => ['sometimes', 'boolean'],
        ], [
            // Mensajes propios del formulario, independientes del idioma global.
            'edad.required' => 'Indica tu edad para simular la financiación.',
            'edad.integer' => 'La edad del solicitante debe ser un número entero.',
            'edad.between' => 'La edad del solicitante debe estar entre 0 y 120 años.',
            'importe.required' => 'Indica el importe que quieres financiar.',
            'importe.numeric' => 'El importe de la financiación debe ser un número.',
            'importe.min' => 'El importe de la financiación no puede ser negativo.',
            'antiguedad_meses.required' => 'Indica tu antigüedad laboral en meses.',
            'antiguedad_meses.integer' => 'La antigüedad laboral debe ser un número entero de meses.',
            'antiguedad_meses.between' => 'La antigüedad laboral debe estar entre 0 y 1440 meses.',
            'documentacion_completa.boolean' => 'Indica si la documentación está completa.',
            'importe.regex' => 'Introduce un importe con un máximo de dos decimales.',
        ]);
        if ($datos['antiguedad_meses'] > $datos['edad'] * 12) {
            throw ValidationException::withMessages(['antiguedad_meses' => 'La antigüedad laboral no puede superar la edad del solicitante en meses.']);
        }
        $datos['documentacion_completa'] = $request->boolean('documentacion_completa');

        return view('usuario.financiacion.resultado', ['resultado' => $this->validador->validar($datos)]);
    }
}
