<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    protected $table = 'avisos';

    // Los avisos del tablón solo necesitan título y contenido.
    protected $fillable = ['titulo', 'contenido'];
}
