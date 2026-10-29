<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['tarjeta' => 'Tarjeta simulada', 'transferencia' => 'Transferencia simulada'] as $codigo => $nombre) {
            MetodoPago::firstOrCreate(['codigo' => $codigo], ['nombre' => $nombre, 'activo' => true]);
        }
    }
}
