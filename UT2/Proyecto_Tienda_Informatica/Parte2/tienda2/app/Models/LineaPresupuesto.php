<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LineaPresupuesto extends Model
{
    protected $table = 'lineas_presupuesto';

    protected $fillable = ['producto_id', 'producto_nombre', 'cantidad', 'precio_centimos'];

    protected function casts(): array
    {
        return ['cantidad' => 'integer', 'precio_centimos' => 'integer'];
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
