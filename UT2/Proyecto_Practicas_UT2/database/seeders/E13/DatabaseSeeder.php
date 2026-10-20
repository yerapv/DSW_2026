<?php

namespace Database\Seeders\E13;

use App\Ejercicios\E13\Models\Monitor;
use App\Ejercicios\E13\Models\Taller;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $m1 = Monitor::firstOrCreate(['nombre' => 'Ana']);
        $m2 = Monitor::firstOrCreate(['nombre' => 'Luis']);
        foreach (range(1, 18) as $i) {
            Taller::firstOrCreate(['monitor_id' => $i % 2 ? $m1->id : $m2->id, 'titulo' => 'Taller '.$i, 'nivel' => ['inicial', 'medio', 'avanzado'][$i % 3]]);
        }
    }
}
