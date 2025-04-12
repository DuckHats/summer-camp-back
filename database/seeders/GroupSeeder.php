<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Group;
use App\Models\Photo;
use App\Models\ScheduledActivity;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        Group::factory(5)->create()->each(function (Group $group) {
            $activities = Activity::factory(3)->create();

            foreach ($activities as $activity) {
                for ($i = 0; $i < rand(1, 5); $i++) {
                    ScheduledActivity::factory([
                        'activity_id' => $activity->id,
                        'group_id' => $group->id,
                    ]);
                }
            }

            Photo::factory(3)->create([
                'group_id' => $group->id,
            ]);
        });
    }
}
