<?php

namespace App\Ejercicios\E11\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Libro extends Model
{
    protected $table = 'libros_editoriales';

    protected $fillable = ['titulo', 'editorial_id'];

    public function editorial(): BelongsTo
    {
        return $this->belongsTo(Editorial::class);
    }
}
