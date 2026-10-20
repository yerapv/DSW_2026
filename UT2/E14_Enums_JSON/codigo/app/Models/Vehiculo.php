<?php

namespace App\Models;

use App\Enums\TipoVehiculo;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $fillable = ['nombre', 'matricula', 'tipo', 'caracteristicas'];
    protected function casts(): array
    {
        return ['tipo' => TipoVehiculo::class, 'caracteristicas' => 'array'];
    }
}
