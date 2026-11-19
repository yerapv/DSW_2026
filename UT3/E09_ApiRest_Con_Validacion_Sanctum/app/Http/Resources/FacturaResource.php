<?php

namespace App\Http\Resources;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacturaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'cantidad' => $this->cantidad,
            'estado' => $this->estado,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_pago' => $this->fecha_pago,
            //'cliente' => Cliente::find($this->cliente_id)
        ];
    }
}
