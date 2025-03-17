<?php

namespace Database\Seeders;

use App\Models\PhoneVerification;
use Illuminate\Database\Seeder;

class PhoneVerificationSeeder extends Seeder
{
    public function run()
    {
        PhoneVerification::factory()->count(10)->create();
    }
}
