<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    // Especificamos la tabla para no depender de la pluralización en inglés.
    protected $table = 'categorias';

    // Solo estos atributos pueden asignarse mediante create() o update().
    protected $fillable = ['nombre'];

    /** Una categoría puede tener muchos productos. */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
