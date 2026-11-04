<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    // Se define el método casts para indicar que el campo 'password' debe ser tratado como un valor hasheado.
    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // Definimos la relación con el modelo Nota, indicando que un usuario puede tener muchas notas.
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }
}
