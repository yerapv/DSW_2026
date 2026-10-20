<?php

namespace Database\Seeders;

use App\Models\Monitor;
use App\Models\Taller;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $m1 = Monitor::create(['nombre' => 'Ana']);
        $m2 = Monitor::create(['nombre' => 'Luis']);
        foreach (range(1, 18) as $i) {
            Taller::create(['monitor_id' => $i % 2 ? $m1->id : $m2->id, 'titulo' => 'Taller ' . $i, 'nivel' => ['inicial', 'medio', 'avanzado'][$i % 3]]);
        }
    }
}
