<?php

namespace Database\Seeders\E12;

use App\Ejercicios\E12\Models\Actividad;
use App\Ejercicios\E12\Models\Alumno;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Alumno::firstOrCreate(['nombre' => 'Alumno Demo']);
        Actividad::firstOrCreate(['nombre' => 'Ajedrez']);
        Actividad::firstOrCreate(['nombre' => 'Teatro']);
        Actividad::firstOrCreate(['nombre' => 'Robótica']);
    }
}
