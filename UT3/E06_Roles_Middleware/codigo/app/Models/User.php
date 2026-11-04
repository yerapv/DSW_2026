<?php

namespace App\Models;

use App\Enums\Rol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // fillable es un array que define los atributos que se pueden asignar masivamente al modelo. Esto es útil para proteger contra la asignación masiva de atributos no deseados.
    protected $fillable = ['name', 'email', 'password', 'rol'];

    // hidden es un array que define los atributos que deben ocultarse cuando el modelo se convierte a un array o JSON. Esto es útil para proteger información sensible, como contraseñas y tokens de recordatorio.
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        // Aquí se define que el atributo 'rol' del modelo User debe ser tratado como una instancia de la enumeración Rol. Esto permite que al acceder a $user->rol, se obtenga un objeto de tipo Rol en lugar de un simple string.
        return ['password' => 'hashed', 'rol' => Rol::class, 'email_verified_at' => 'datetime'];
    }
}
