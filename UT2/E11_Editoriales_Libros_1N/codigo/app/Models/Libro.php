<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Libro extends Model
{
    protected $fillable = ['titulo', 'editorial_id'];
    public function editorial(): BelongsTo
    {
        return $this->belongsTo(Editorial::class);
    }
}
