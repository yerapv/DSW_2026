<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = ['nombre', 'email', 'direccion', 'tipo', 'edad'];

    protected function casts(): array
    {
        return ['edad' => 'integer'];
    }

    public function carrito(): HasOne
    {
        return $this->hasOne(Carrito::class);
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }
}
