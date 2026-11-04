<?php

namespace App\Ejercicios\E07\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    protected $table = 'notas';

    protected $fillable = ['titulo', 'contenido'];

    // Definimos la relación con el modelo User relación de muchos a uno (muchas notas pertenecen a un usuario)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
