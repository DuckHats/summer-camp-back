<?php

namespace Database\Factories;

use App\Models\Error;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErrorFactory extends Factory
{
    protected $model = Error::class;

    public function definition()
    {
        return [
            'error_code' => $this->faker->word(),
            'error_message' => $this->faker->sentence(),
            'stack_trace' => $this->faker->paragraph(),
            'user_id' => User::inRandomOrder()->first()->id ?? null,
            'session_id' => $this->faker->uuid(),
            'occurred_at' => $this->faker->dateTime(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
