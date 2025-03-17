<?php

namespace Database\Seeders;

use App\Models\PasswordReset;
use Illuminate\Database\Seeder;

class PasswordResetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PasswordReset::factory()->count(10)->create();
    }
}
