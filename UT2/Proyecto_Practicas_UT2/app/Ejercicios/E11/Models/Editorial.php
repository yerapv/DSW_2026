<?php

namespace App\Ejercicios\E11\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Editorial extends Model
{
    protected $table = 'editoriales';

    protected $fillable = ['nombre'];

    public function libros(): HasMany
    {
        return $this->hasMany(Libro::class);
    }
}
