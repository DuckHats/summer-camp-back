<?php

namespace App\Policies;

use App\Models\User;

class ActivityPolicy
{
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
    public function view(User $requestUser, User $user): bool
    {
        return $user->isAdmin() || $user->id == $requestUser->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
