<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarClienteRequest extends FormRequest
{
    /** La ficha del cliente es pública, sin inicio de sesión. */
    public function authorize(): bool
    {
        return true;
    }

    /** Estas reglas comprueban los campos antes de ejecutar store(). */
    public function rules(): array
    {
        return [
            'cliente_id' => ['nullable', 'integer', 'exists:clientes,id'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'nombre' => ['required', 'string', 'max:80'],
            'tipo' => ['required', 'in:particular,empresa,estudiante'],
            'edad' => ['required', 'integer', 'between:0,120'],
            'email' => ['required', 'email', 'max:150'],
        ];
    }

    /** Laravel utiliza campo.regla para mostrar un mensaje acorde a este formulario. */
    public function messages(): array
    {
        return [
            'cliente_id.integer' => 'Selecciona un cliente válido.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
            'direccion.string' => 'La dirección debe ser un texto.',
            'direccion.max' => 'La dirección no puede superar 255 caracteres.',
            'nombre.required' => 'Indica el nombre de la persona de la ficha de cliente.',
            'nombre.string' => 'El nombre del cliente debe ser un texto.',
            'nombre.max' => 'El nombre del cliente no puede superar :max caracteres.',
            'tipo.required' => 'Selecciona el tipo de cliente de la ficha.',
            'tipo.in' => 'El tipo de cliente debe ser particular, empresa o estudiante.',
            'edad.required' => 'Indica la edad del cliente.',
            'edad.integer' => 'La edad del cliente debe ser un número entero.',
            'edad.between' => 'La edad del cliente debe estar entre :min y :max años.',
            'email.required' => 'Indica el correo electrónico del cliente.',
            'email.email' => 'Introduce un correo electrónico válido para la ficha del cliente.',
            'email.max' => 'El correo electrónico del cliente no puede superar :max caracteres.',
        ];
    }
}
