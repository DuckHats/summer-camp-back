<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function create(User $user, User $requestUser): bool
    {
        return $user->id == $requestUser->id  || $user->isAdmin();
    }

    public function update(User $user, User $requestUser): bool
    {
        return $user->id == $requestUser->id  || $user->isAdmin();
    }

    public function delete(User $user, User $requestUser): bool
    {
        return $user->id == $requestUser->id  || $user->isAdmin();
    }

    public function disable(User $user, User $requestUser): bool
    {
        return $user->id == $requestUser->id  || $user->isAdmin();
    }

    public function enable(User $adminUser): bool
    {
        return $adminUser->isAdmin();
    }
}
