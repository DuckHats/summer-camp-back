<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        $types = ['alert', 'error', 'security', 'ad', 'info'];

        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement($types),
            'data' => json_encode(['message' => $this->faker->sentence]),
            'read_at' => $this->faker->optional()->dateTime,
        ];
    }
}
