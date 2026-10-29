<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';

    protected $fillable = ['cliente_id', 'datos_cliente', 'total_centimos', 'instantanea'];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function lineas(): HasMany
    {
        return $this->hasMany(LineaPresupuesto::class)->orderBy('id');
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'lineas_presupuesto')
            ->withPivot('producto_nombre', 'cantidad', 'precio_centimos')->withTimestamps();
    }

    public function nombreCliente(): string
    {
        return $this->datos_cliente['nombre'] ?? 'Sin cliente asignado';
    }

    /** El JSON almacenado en BD se recupera como un array PHP. */
    protected function casts(): array
    {
        return ['total_centimos' => 'integer', 'instantanea' => 'array', 'datos_cliente' => 'array'];
    }
}
