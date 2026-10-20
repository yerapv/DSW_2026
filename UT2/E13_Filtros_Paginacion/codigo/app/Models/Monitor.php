<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $fillable = ['nombre'];
    public function talleres()
    {
        return $this->hasMany(Taller::class);
    }
}
