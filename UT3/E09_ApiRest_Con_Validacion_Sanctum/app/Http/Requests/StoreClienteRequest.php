<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo permite usuarios que tengan permisos de creación
        $usuario = $this->user();
        return $usuario != null && $usuario->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required'],
            'tipo' => ['required', Rule::in(['I', 'E', 'i', 'e'])],
            'email' => ['required', 'email'],
            'direccion' => ['required'],
            'ciudad' => ['required'],
            'codigo_postal' => ['required']
        ];
    }
}
