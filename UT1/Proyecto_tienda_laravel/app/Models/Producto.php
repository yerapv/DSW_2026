<?php

namespace App\Models;

use Database\Factories\ProductoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    /** @use HasFactory<ProductoFactory> */
    use HasFactory;

    protected $fillable = ['sku', 'nombre', 'categoria_id', 'descripcion', 'precio', 'stock', 'activo'];

    protected function casts(): array
    {
        return ['precio' => 'decimal:2', 'stock' => 'integer', 'activo' => 'boolean'];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }
}
