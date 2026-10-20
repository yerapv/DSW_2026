<?php

namespace Database\Seeders;

use App\Models\Editorial;
use App\Models\Libro;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $a = Editorial::create(['nombre' => 'Norte']);
        $b = Editorial::create(['nombre' => 'Sur']);
        Libro::create(['titulo' => 'Isla de papel', 'editorial_id' => $a->id]);
        Libro::create(['titulo' => 'Luz de invierno', 'editorial_id' => $a->id]);
        Libro::create(['titulo' => 'Mar abierto', 'editorial_id' => $b->id]);
    }
}
