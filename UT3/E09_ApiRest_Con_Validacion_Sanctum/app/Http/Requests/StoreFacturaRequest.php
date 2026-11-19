<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFacturaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->tokenCan('create') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'cantidad' => ['required', 'integer', 'min:0'],
            'estado' => ['required', Rule::in(['F', 'P', 'C', 'A'])],
            'fecha_creacion' => ['required', 'date'],
            'fecha_pago' => ['nullable', 'date']
        ];
    }
}
