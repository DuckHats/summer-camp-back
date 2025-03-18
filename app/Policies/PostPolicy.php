<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the user can create a post.
     */
    public function create(User $user, Post $post): bool
    {
        return $user->id == $post->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can update a post.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id == $post->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete a post.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id == $post->user_id || $user->isAdmin();
    }
}
