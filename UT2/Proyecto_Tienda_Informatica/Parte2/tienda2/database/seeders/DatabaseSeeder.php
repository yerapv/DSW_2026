<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Punto de entrada de db:seed o migrate --seed; no inserta registros directamente.
class DatabaseSeeder extends Seeder
{
    /** Llama a un seeder por tabla, creando primero los datos de los que dependen otros. */
    public function run(): void
    {
        // Los productos necesitan categorías.
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
            ClienteSeeder::class,
            MetodoPagoSeeder::class,
            AvisoSeeder::class,
        ]);
    }
}
