<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /** Mantiene en esta clase únicamente los datos de su tabla. */
    public function run(): void
    {
        // Se conserva el mismo conjunto de nombres del ejemplo integrado.
        foreach (['Ana García' => 'ana@example.test', 'Luis Pérez' => 'luis@example.test', 'Marta Díaz' => 'marta@example.test'] as $nombre => $email) {
            Cliente::firstOrCreate(['nombre' => $nombre], ['email' => $email]);
        }
    }
}
