<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\ScheduledActivity;

class ScheduledActivitySeeder extends Seeder
{
    public function run()
    {
        Activity::factory()
            ->count(10)
            ->create()
            ->each(function ($activity) {
                ScheduledActivity::factory()
                    ->count(rand(1, 5))
                    ->create([
                        'activity_id' => $activity->id,
                    ]);
            });
    }
}
