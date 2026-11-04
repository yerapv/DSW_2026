<?php

namespace Database\Seeders;

use App\Ejercicios\E06\Enums\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['Socio', 'yeray@yeray.test', Rol::Socio], ['Gestor', 'gestor@test.test', Rol::Gestor], ['Otro Socio', 'otra@test.test', Rol::Socio]] as [$name, $email, $rol]) {
            if (! User::where('email', $email)->exists()) {
                $user = new User(['name' => $name, 'email' => $email, 'password' => 'password']);
                $user->rol = $rol;
                $user->save();
            }
        }
    }
}
