<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Alumno;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Alumno::create(['nombre' => 'Alumno Demo']);
        Actividad::create(['nombre' => 'Ajedrez']);
        Actividad::create(['nombre' => 'Teatro']);
        Actividad::create(['nombre' => 'Robótica']);
    }
}
