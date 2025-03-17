<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dni' => $this->faker->randomNumber(8, true). $this->faker->randomLetter,
            'username' => $this->faker->unique()->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'status' => $this->faker->numberBetween(0, 3),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->randomNumber(9, true),
            'profile_picture_url' => $this->faker->imageUrl(640, 480),
            'profile_extra_info' => $this->faker->paragraph,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'password' => bcrypt('password'),
            'birth_date' => $this->faker->date(),
            'remember_token' => $this->faker->sha256,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
