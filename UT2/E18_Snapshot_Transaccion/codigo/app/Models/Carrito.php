<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $guarded = [];
    public function lineas()
    {
        return $this->hasMany(LineaCarrito::class);
    }
}
