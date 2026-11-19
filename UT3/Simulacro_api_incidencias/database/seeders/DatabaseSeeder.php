<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Independiente de los archivos que debe crear el alumnado.
        foreach (['alumno' => 'socio', 'gestor' => 'gestor'] as $nombre => $rol) {
            if (!DB::table('users')->where('email', $nombre.'@example.com')->exists()) {
                DB::table('users')->insert([
                    'name' => ucfirst($nombre), 'email' => $nombre.'@example.com',
                    'password' => Hash::make('Password123!'), 'rol' => $rol,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }

        foreach ([
            ['titulo' => 'Proyector averiado', 'descripcion' => 'El proyector del aula no enciende.', 'estado' => 'abierta'],
            ['titulo' => 'Ventana reparada', 'descripcion' => 'La ventana de la biblioteca ya cierra correctamente.', 'estado' => 'cerrada'],
        ] as $datos) {
            if (!DB::table('incidencias')->where('titulo', $datos['titulo'])->exists()) {
                DB::table('incidencias')->insert(array_merge($datos, ['created_at' => now(), 'updated_at' => now()]));
            }
        }
    }
}
