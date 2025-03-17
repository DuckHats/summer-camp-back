<?php

namespace Database\Seeders;

use App\Models\EmailVerification;
use Illuminate\Database\Seeder;

class EmailVerificationSeeder extends Seeder
{
    public function run()
    {
        EmailVerification::factory()->count(10)->create();
    }
}
