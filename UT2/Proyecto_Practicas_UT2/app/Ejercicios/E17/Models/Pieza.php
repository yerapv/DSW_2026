<?php

namespace App\Ejercicios\E17\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pieza extends Model
{
    protected $table = 'piezas';

    protected $fillable = ['nombre', 'autor', 'ruta_imagen'];

    protected static function booted(): void
    {
        static::deleted(function (Pieza $p): void {
            if ($p->ruta_imagen) {
                Storage::disk('public')->delete($p->ruta_imagen);
            }
        });
    }
}
