<?php

namespace App\Ejercicios\E18\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = ['nombre', 'precio_dia_centimos'];
}
