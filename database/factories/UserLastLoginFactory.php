<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserLastLogin>
 */
class UserLastLoginFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'last_login_at' => $this->faker->dateTimeThisYear,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
