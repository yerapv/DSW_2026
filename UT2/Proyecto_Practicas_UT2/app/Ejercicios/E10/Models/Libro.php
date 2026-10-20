<?php

namespace App\Ejercicios\E10\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';

    protected $fillable = ['titulo', 'autor', 'anio', 'disponible'];

    protected function casts(): array
    {
        return ['disponible' => 'boolean'];
    }
}
