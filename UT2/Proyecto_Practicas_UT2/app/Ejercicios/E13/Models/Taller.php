<?php

namespace App\Ejercicios\E13\Models;

use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    protected $table = 'talleres';

    protected $fillable = ['monitor_id', 'titulo', 'nivel'];

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
