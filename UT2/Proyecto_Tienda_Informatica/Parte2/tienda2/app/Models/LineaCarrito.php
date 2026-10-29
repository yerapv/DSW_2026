<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LineaCarrito extends Model
{
    protected $table = 'lineas_carrito';

    protected $fillable = ['carrito_id', 'producto_id', 'cantidad', 'precio_centimos'];

    /** Los importes permanecen como enteros para sumarlos sin errores de coma. */
    protected function casts(): array
    {
        return ['cantidad' => 'integer', 'precio_centimos' => 'integer'];
    }

    /** El producto aporta su nombre al construir la instantánea. */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /** Esta clave foránea identifica el carrito que contiene la línea. */
    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class);
    }
}
