<?php

namespace App\Models;

use App\Ejercicios\E06\Enums\Rol;
use App\Ejercicios\E07\Models\Nota;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $attributes = ['rol' => 'socio'];

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed', 'rol' => Rol::class, 'email_verified_at' => 'datetime'];
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }
}
