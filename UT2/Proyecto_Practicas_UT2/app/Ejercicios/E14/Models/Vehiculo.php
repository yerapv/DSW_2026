<?php

namespace App\Ejercicios\E14\Models;

use App\Ejercicios\E14\Enums\TipoVehiculo;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = ['nombre', 'matricula', 'tipo', 'caracteristicas'];

    protected function casts(): array
    {
        return ['tipo' => TipoVehiculo::class, 'caracteristicas' => 'array'];
    }
}
