<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genero extends Model
{
    // Eloquent asocia este modelo con la tabla generos y su clave primaria id.
    // $fillable permite guardar nombre mediante create, update o firstOrCreate.
    protected $fillable = ['nombre'];

    // Relación 1:N: un género tiene muchos videojuegos.
    // Eloquent busca los registros cuyo genero_id coincide con el id de este género.
    public function videojuegos(): HasMany
    {
        return $this->hasMany(Videojuego::class);
    }
}
