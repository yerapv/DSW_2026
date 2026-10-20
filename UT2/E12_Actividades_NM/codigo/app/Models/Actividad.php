<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Actividad extends Model
{
    protected $fillable = ['nombre'];
    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class)->withPivot('fecha_inscripcion');
    }
}
