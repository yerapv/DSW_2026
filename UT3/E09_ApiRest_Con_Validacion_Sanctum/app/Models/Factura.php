<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    use HasFactory;

    protected $table = "facturas";

    // Asignar campos que serán rellenables desde una Request.
    protected $fillable = [
        'cliente_id',
        'cantidad',
        'estado',
        'fecha_creacion',
        'fecha_pago'
    ];

    // Por cada factura, solo vamos a tener un cliente.
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
