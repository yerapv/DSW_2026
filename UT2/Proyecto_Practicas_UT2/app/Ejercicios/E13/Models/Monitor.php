<?php

namespace App\Ejercicios\E13\Models;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $table = 'monitores';

    protected $fillable = ['nombre'];

    public function talleres()
    {
        return $this->hasMany(Taller::class);
    }
}
