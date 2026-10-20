<?php

namespace App\Ejercicios\E09\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'notas';

    // Se define el nombre de la tabla (cuidado con las palabras en español)

    // Se definen los campos que se van a utilizar, solo elegimos titulo y contenido para este caso.
    protected $fillable = ['titulo', 'contenido'];
}
