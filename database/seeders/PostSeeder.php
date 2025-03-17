<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(10)->create();

        Post::factory(20)
            ->create()
            ->each(function ($post) use ($users) {
                $post->user()->associate($users->random())->save();
            });
    }
}
