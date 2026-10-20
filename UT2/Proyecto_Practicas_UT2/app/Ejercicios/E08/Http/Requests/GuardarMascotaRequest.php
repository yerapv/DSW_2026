<?php

namespace App\Ejercicios\E08\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarMascotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80'],
            'especie' => ['required', 'in:perro,gato,otro'],
            'edad' => ['required', 'integer', 'between:0,30'],
            'responsable' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Indica el nombre de la mascota.',
            'especie.required' => 'Selecciona la especie de la mascota.',
            'edad.required' => 'Indica la edad de la mascota.',
            'responsable.required' => 'Indica el nombre de la persona responsable de la mascota.',
        ];
    }
}
