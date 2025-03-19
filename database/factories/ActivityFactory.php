<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Activity;
use App\Models\Group;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        $initial_hour = $this->faker->time('H:i:s');
        $final_hour = date('H:i:s', strtotime($initial_hour) + 3600);
        $duration = 60;

        return [
            'name' => $this->faker->sentence(3),
            'initial_hour' => $initial_hour,
            'final_hour' => $final_hour,
            'duration' => $duration,
            'description' => $this->faker->paragraph(),
            'cover_image' => $this->faker->imageUrl(),
            'location' => $this->faker->address(),
            'group_id' => Group::factory(),
        ];
    }
}
