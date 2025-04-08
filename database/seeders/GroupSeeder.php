<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\Activity;
use App\Models\ScheduledActivity;
use App\Models\Photo;
use Illuminate\Support\Carbon;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        Group::factory(5)->create()->each(function (Group $group) {
            $activities = Activity::factory(3)->create([
                'group_id' => $group->id,
            ]);

            foreach ($activities as $activity) {
                for ($i = 0; $i < rand(1, 5); $i++) {
                    ScheduledActivity::factory([
                        'activity_id' => $activity->id,
                    ]);
                }
            }

            Photo::factory(3)->create([
                'group_id' => $group->id,
            ]);
        });
    }
}
