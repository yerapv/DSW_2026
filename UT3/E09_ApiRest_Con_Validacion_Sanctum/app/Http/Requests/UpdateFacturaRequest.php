<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFacturaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->tokenCan('update') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente_id' => ['sometimes', 'required', 'integer', 'exists:clientes,id'],
            'cantidad' => ['sometimes', 'required', 'integer', 'min:0'],
            'estado' => ['sometimes','required', Rule::in(['F','P','C', 'A'])],
            'fecha_creacion' => ['sometimes', 'required', 'date'],
            'fecha_pago' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
