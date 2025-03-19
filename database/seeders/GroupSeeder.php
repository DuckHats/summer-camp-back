<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Activity;
use App\Models\Day;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run()
    {
        Group::factory(5)->create()->each(function ($group) {
            Activity::factory(3)->create(['group_id' => $group->id])->each(function ($activity) {
                $days = Day::inRandomOrder()->take(rand(1, 5))->get();
                $activity->days()->attach($days);
            });
        });
    }
}
