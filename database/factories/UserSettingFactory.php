<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingFactory extends Factory
{
    protected $model = UserSetting::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'key' => $this->faker->word,
            'value' => $this->faker->word,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
