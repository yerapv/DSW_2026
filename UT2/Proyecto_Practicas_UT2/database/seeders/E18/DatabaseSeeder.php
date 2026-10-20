<?php

namespace Database\Seeders\E18;

use App\Ejercicios\E18\Models\Producto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Producto::firstOrCreate(['nombre' => 'Cámara', 'precio_dia_centimos' => 4000]);
        Producto::firstOrCreate(['nombre' => 'Trípode', 'precio_dia_centimos' => 1000]);
        Producto::firstOrCreate(['nombre' => 'Micrófono', 'precio_dia_centimos' => 1500]);
    }
}
