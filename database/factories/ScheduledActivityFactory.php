<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ScheduledActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledActivityFactory extends Factory
{
    protected $model = ScheduledActivity::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate = (clone $startDate)->modify('+1 hour');

        return [
            'activity_id' => Activity::factory(),
            'initial_date' => $startDate->format('Y-m-d'),
            'final_date' => $endDate->format('Y-m-d'),
            'initial_hour' => $startDate->format('H:i:s'),
            'final_hour' => $endDate->format('H:i:s'),
            'location' => $this->faker->randomElement(['Sala 1', 'Sala 2', 'Pista Exterior', 'Online']),
        ];
    }
}

