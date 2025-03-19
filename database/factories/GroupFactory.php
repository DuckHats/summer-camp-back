<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Monitor;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'profile_picture' => $this->faker->imageUrl(200, 200, 'people', true),
            'monitor_id' => Monitor::factory(),
        ];
    }
}
