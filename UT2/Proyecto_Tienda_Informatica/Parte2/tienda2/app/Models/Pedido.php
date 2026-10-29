<?php

namespace App\Models;

use App\Enums\EstadoPedido;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedido extends Model
{
    // Nombre explícito para que no haya dudas con la pluralización.
    protected $table = 'pedidos';

    protected $fillable = [
        'total_centimos', 'instantanea', 'metodo_pago', 'estado',
        'cliente_id', 'metodo_pago_id', 'datos_cliente', 'metodo_pago_nombre',
    ];

    /** El dinero se trata como entero y la instantánea como un array PHP. */
    protected function casts(): array
    {
        return ['estado' => EstadoPedido::class, 'total_centimos' => 'integer', 'instantanea' => 'array', 'datos_cliente' => 'array'];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class);
    }

    public function nombreCliente(): string
    {
        return $this->datos_cliente['nombre'] ?? 'Sin cliente asignado';
    }

    public function nombreMetodoPago(): string
    {
        return $this->metodo_pago_nombre ?? match ($this->metodo_pago) {
            'tarjeta' => 'Tarjeta simulada',
            'transferencia' => 'Transferencia simulada',
            default => $this->metodo_pago,
        };
    }
}
