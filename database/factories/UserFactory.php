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
            'username' => $this->faker->unique()->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'status' => $this->faker->numberBetween(0, 3),
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'phone' => $this->faker->randomNumber(9, true),
            'phone_verified' => now(),
            'profile_picture_url' => $this->faker->imageUrl(640, 480),
            'profile_short_description' => $this->faker->sentence,
            'profile_description' => $this->faker->paragraph,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'location' => $this->faker->city,
            'password' => bcrypt('password'),
            'birth_date' => $this->faker->date(),
            'cv_path' => $this->faker->word,
            'portfolio_url' => $this->faker->url,
            'level' => $this->faker->numberBetween(1, 10),
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
