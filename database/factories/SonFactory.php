<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Son;
use App\Models\Group;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Son>
 */
class SonFactory extends Factory
{
    protected $model = Son::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'dni' => $this->faker->unique()->randomNumber(8, true). $this->faker->randomLetter,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'birth_date' => $this->faker->date(),
            'group_id' => Group::factory(),
            'profile_picture_url' => $this->faker->imageUrl(640, 480),
            'profile_extra_info' => $this->faker->paragraph,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'user_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
