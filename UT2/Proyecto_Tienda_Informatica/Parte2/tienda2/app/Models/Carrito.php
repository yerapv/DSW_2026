<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    protected $table = 'carritos';

    protected $fillable = ['cliente_id'];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public static function paraCliente(Cliente $cliente): self
    {
        return self::firstOrCreate(['cliente_id' => $cliente->id]);
    }

    /** Cada carrito puede contener varias líneas. */
    public function lineas(): HasMany
    {
        return $this->hasMany(LineaCarrito::class);
    }
}
