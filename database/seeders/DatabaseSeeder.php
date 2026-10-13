<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed data awal aplikasi.
     */
    public function run(): void
    {
        $this->call([
            ProfessionalSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
