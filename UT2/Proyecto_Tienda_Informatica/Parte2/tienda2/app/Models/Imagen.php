<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

// Mantenemos nombre, autor y ruta, ahora para fotografías de informática.
class Imagen extends Model
{
    protected $table = 'escaparate_imagenes';

    protected $fillable = ['nombre', 'autor', 'ruta_imagen'];

    /** El evento deleted coordina el borrado del registro con el del archivo. */
    protected static function booted(): void
    {
        static::deleted(function (Imagen $imagen): void {
            // Guardamos una ruta relativa, nunca el contenido del archivo en BD.
            if ($imagen->ruta_imagen) {
                Storage::disk('public')->delete($imagen->ruta_imagen);
            }
        });
    }
}
