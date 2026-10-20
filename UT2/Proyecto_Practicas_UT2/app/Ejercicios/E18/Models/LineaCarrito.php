<?php

namespace App\Ejercicios\E18\Models;

use Illuminate\Database\Eloquent\Model;

class LineaCarrito extends Model
{
    protected $table = 'lineas_carrito';

    protected $fillable = ['carrito_id', 'producto_id', 'dias', 'precio_centimos'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }
}
