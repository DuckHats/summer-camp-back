<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seeders = array_filter(
            glob(database_path('seeders/*Seeder.php')),
            fn ($seeder) => pathinfo($seeder, PATHINFO_FILENAME) !== 'DatabaseSeeder'
        );

        foreach ($seeders as $seeder) {
            $seederClass = 'Database\\Seeders\\'.pathinfo($seeder, PATHINFO_FILENAME);

            if (class_exists($seederClass)) {
                $this->call($seederClass);
            }
        }
    }
}
