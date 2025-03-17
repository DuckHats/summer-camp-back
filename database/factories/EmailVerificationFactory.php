<?php

namespace Database\Factories;

use App\Models\EmailVerification;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailVerificationFactory extends Factory
{
    protected $model = EmailVerification::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'verification_code' => $this->faker->numerify('########'),
            'expires_at' => $this->faker->dateTimeBetween('+1 minutes', '+15 minutes'),
        ];
    }
}
