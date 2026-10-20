<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alumno extends Model
{
    protected $table = "alumnos";

    protected $fillable = ['nombre'];
    public function actividades(): BelongsToMany
    {
        return $this->belongsToMany(Actividad::class)->withPivot('fecha_inscripcion');
    }
}
