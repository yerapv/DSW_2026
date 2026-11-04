<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    protected $fillable = ['titulo', 'contenido'];
    // Definimos la relación con el modelo User relación de muchos a uno (muchas notas pertenecen a un usuario)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
