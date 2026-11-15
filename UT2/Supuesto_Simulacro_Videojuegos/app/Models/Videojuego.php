<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Videojuego extends Model
{
    // Campos que create y update pueden asignar de una vez a un registro.
    // imagen contiene la ruta relativa de la portada, no el contenido del archivo.
    protected $fillable = ['titulo', 'precio', 'genero_id', 'imagen'];

    // Cada videojuego pertenece a un género mediante la columna genero_id.
    // En Blade se puede acceder a su nombre con $videojuego->genero->nombre.
    public function genero(): BelongsTo
    {
        return $this->belongsTo(Genero::class);
    }
}
