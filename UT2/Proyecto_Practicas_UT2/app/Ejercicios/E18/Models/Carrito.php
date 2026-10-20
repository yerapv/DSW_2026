<?php

namespace App\Ejercicios\E18\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';

    protected $guarded = [];

    public function lineas()
    {
        return $this->hasMany(LineaCarrito::class);
    }
}
