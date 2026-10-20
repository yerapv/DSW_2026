<?php

namespace App\Ejercicios\E12\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = ['nombre'];

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class)->withPivot('fecha_inscripcion');
    }
}
