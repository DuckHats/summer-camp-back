<?php

namespace Database\Factories;

use App\Models\Policy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PolicyFactory extends Factory
{
    protected $model = Policy::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'accept_newsletter' => $this->faker->randomElement([0, 1]),
            'accept_privacy_policy' => $this->faker->randomElement([0, 1]),
            'accept_terms_of_use' => $this->faker->randomElement([0, 1]),
        ];
    }
}
