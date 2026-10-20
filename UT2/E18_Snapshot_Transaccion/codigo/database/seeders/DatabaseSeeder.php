<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Producto::create(['nombre' => 'Cámara', 'precio_dia_centimos' => 4000]);
        Producto::create(['nombre' => 'Trípode', 'precio_dia_centimos' => 1000]);
        Producto::create(['nombre' => 'Micrófono', 'precio_dia_centimos' => 1500]);
    }
}
