<?php

namespace Database\Seeders;

use App\Models\Genero;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Carga los géneros del desplegable con db:seed o migrate --seed.
        foreach (['Aventura', 'Deportes', 'Estrategia'] as $nombre) {
            // Busca el nombre y solo crea el registro si todavía no existe.
            // Así, repetir el seeder no duplica estos géneros.
            Genero::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
