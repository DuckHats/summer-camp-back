<?php

namespace Database\Factories;

use App\Models\PhoneVerification;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneVerificationFactory extends Factory
{
    protected $model = PhoneVerification::class;

    public function definition()
    {
        return [
            'phone' => $this->faker->unique()->phoneNumber(),
            'verification_code' => $this->faker->numerify('########'),
            'expires_at' => $this->faker->dateTimeBetween('+1 minutes', '+15 minutes'),
        ];
    }
}
