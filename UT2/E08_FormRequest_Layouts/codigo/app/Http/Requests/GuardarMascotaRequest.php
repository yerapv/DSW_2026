<?php

namespace App\Http\Requests;

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
}
