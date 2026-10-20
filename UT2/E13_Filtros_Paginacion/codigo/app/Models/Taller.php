<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    protected $fillable = ['monitor_id', 'titulo', 'nivel'];
    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
