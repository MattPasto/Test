<?php

namespace Database\Seeders;

use Database\Seeders\FilialiSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AutomezziSeeder;

class DatabaseSeeder extends Seeder
{
    const SEEDER = [
        FilialiSeeder::class,
        AutomezziSeeder::class
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(self::SEEDER);
    }
}
