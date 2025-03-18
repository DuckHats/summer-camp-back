<?php

namespace App\Policies;

use App\Models\Son;
use App\Models\User;

class SonPolicy
{
    public function create(User $user, Son $son): bool
    {
        return $user->id == $son->user_id || $user->isAdmin();
    }

    public function update(User $user, Son $son): bool
    {
        return $user->id == $son->user_id || $user->isAdmin();
    }

    public function delete(User $user, Son $son): bool
    {
        return $user->id == $son->user_id || $user->isAdmin();
    }
}
