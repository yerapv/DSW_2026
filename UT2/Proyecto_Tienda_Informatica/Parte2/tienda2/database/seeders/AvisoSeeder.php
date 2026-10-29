<?php

namespace Database\Seeders;

use App\Models\Aviso;
use Illuminate\Database\Seeder;

class AvisoSeeder extends Seeder
{
    /** Mantiene en esta clase únicamente los datos de su tabla. */
    public function run(): void
    {
        // firstOrCreate evita repetir el mismo título al ejecutar db:seed otra vez.
        Aviso::firstOrCreate(['titulo' => 'Bienvenido a '.config('tienda.nombre')], [
            'contenido' => 'Consulta el catálogo o solicita un presupuesto de montaje.',
        ]);
    }
}
