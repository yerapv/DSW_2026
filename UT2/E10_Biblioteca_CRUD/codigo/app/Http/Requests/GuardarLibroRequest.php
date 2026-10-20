<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarLibroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return ['titulo' => ['required', 'max:150'], 'autor' => ['required', 'max:120'], 'anio' => ['required', 'integer', 'between:1500,' . date('Y')], 'disponible' => ['sometimes', 'boolean']];
    }
    protected function prepareForValidation(): void
    {
        $this->merge(['disponible' => $this->boolean('disponible')]);
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'Indica el título del libro.',
            'autor.required' => 'Indica el autor del libro.',
            'anio.required' => 'Indica el año de publicación del libro.',
        ];
    }

}
