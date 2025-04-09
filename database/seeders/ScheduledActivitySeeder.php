<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ScheduledActivity;
use Illuminate\Database\Seeder;

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
