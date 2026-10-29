<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /** Mantiene en esta clase únicamente los datos de su tabla. */
    public function run(): void
    {
        // firstOrCreate permite repetir el seeder sin duplicar las categorías.
        foreach (['Portátiles', 'Periféricos', 'Componentes'] as $nombre) {
            Categoria::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
