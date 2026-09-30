<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Portátiles', 'Monitores', 'Periféricos', 'Almacenamiento', 'Componentes'] as $nombre) {
            Categoria::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
