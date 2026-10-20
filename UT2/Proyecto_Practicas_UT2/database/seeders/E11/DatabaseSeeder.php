<?php

namespace Database\Seeders\E11;

use App\Ejercicios\E11\Models\Editorial;
use App\Ejercicios\E11\Models\Libro;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $a = Editorial::firstOrCreate(['nombre' => 'Norte']);
        $b = Editorial::firstOrCreate(['nombre' => 'Sur']);
        Libro::firstOrCreate(['titulo' => 'Isla de papel', 'editorial_id' => $a->id]);
        Libro::firstOrCreate(['titulo' => 'Luz de invierno', 'editorial_id' => $a->id]);
        Libro::firstOrCreate(['titulo' => 'Mar abierto', 'editorial_id' => $b->id]);
    }
}
