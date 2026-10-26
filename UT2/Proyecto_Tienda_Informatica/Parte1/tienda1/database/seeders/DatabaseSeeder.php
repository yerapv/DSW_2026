<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Los productos necesitan que sus categorías existan primero.
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
            AvisoSeeder::class,
        ]);
    }
}
