<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            E11\DatabaseSeeder::class,
            E12\DatabaseSeeder::class,
            E13\DatabaseSeeder::class,
            E18\DatabaseSeeder::class,
        ]);
    }
}
