<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    // Se define el nombre de la tabla (cuidado con las palabras en español)
    protected $table = "notas";
    
    // Se definen los campos que se van a utilizar, solo elegimos titulo y contenido para este caso.
    protected $fillable = ['titulo', 'contenido'];
}
