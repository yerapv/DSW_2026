<?php

namespace App\Models;

use App\Enums\TipoProducto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    protected $table = 'productos';

    // create($datos) solo aceptará estas columnas; nunca enviamos request()->all().
    protected $fillable = [
        'nombre', 'marca', 'anio', 'disponible', 'categoria_id',
        'precio_centimos', 'tipo', 'caracteristicas', 'ruta_imagen',
    ];

    /** Eloquent convierte automáticamente los valores al leer y escribir. */
    protected function casts(): array
    {
        return [
            'disponible' => 'boolean',
            'precio_centimos' => 'integer',
            'tipo' => TipoProducto::class,
            'caracteristicas' => 'array',
        ];
    }

    /** Al eliminar el registro también retiramos su fotografía del disco. */
    protected static function booted(): void
    {
        static::deleted(function (Producto $producto): void {
            if ($producto->ruta_imagen) {
                Storage::disk('public')->delete($producto->ruta_imagen);
            }
        });
    }

    /** Cada producto pertenece a una categoría mediante categoria_id. */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function presupuestos(): BelongsToMany
    {
        return $this->belongsToMany(Presupuesto::class, 'lineas_presupuesto')
            ->withPivot('producto_nombre', 'cantidad', 'precio_centimos')->withTimestamps();
    }
}
