<?php

namespace Database\Seeders;

use App\Models\UserLastLogin;
use Illuminate\Database\Seeder;

class UserLastLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserLastLogin::factory()->count(10)->create();
    }
}
