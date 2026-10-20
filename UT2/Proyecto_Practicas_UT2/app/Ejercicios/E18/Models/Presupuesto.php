<?php

namespace App\Ejercicios\E18\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';

    protected $fillable = ['total_centimos', 'instantanea'];

    protected function casts(): array
    {
        return ['instantanea' => 'array'];
    }
}
