<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        $initial_hour = $this->faker->time('H:i:s');

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'cover_image' => 'https://picsum.photos/800',
        ];
    }
}
