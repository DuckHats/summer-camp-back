<?php

namespace Database\Seeders;

use App\Models\Error;
use App\Models\Notification;
use App\Models\Policy;
use App\Models\User;
use App\Models\Son;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::factory(10)->create()->each(function (User $user) {

            UserSetting::factory(rand(1, 3))->create(['user_id' => $user->id]);
            Son::factory(rand(1, 3))->create(['user_id' => $user->id]);

            Policy::create([
                'user_id' => $user->id,
                'accept_newsletter' => rand(0, 1),
                'accept_privacy_policy' => 1,
                'accept_terms_of_use' => 1,
            ]);

            Notification::factory(rand(1, 5))->create([
                'user_id' => $user->id,
                'type' => 'generic',
                'data' => json_encode(['message' => 'Sample notification']),
            ]);

            Error::factory(rand(1, 3))->create([
                'user_id' => $user->id,
                'error_code' => 'ERR' . rand(100, 999),
                'error_message' => 'This is a sample error message.',
                'occurred_at' => now(),
            ]);
        });
    }
}
