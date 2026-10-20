<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $fillable = ['total_centimos', 'instantanea'];
    protected function casts(): array
    {
        return ['instantanea' => 'array'];
    }
}
