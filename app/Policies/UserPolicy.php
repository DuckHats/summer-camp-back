<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function create(User $user, User $requestUser): bool
    {
        return $user->id == $requestUser->id;
    }

    public function update(User $user, User $requestUser): bool
    {
        return $user->id == $requestUser->id;
    }

    public function delete(User $user, User $requestUser): bool
    {
        return $user->id == $requestUser->id;
    }

    public function disable(User $user, User $requestUser): bool
    {
        //this has to be a super admin
        return true;
    }

    public function enable(User $adminUser): bool
    {
        //this has to be a super admin
        return true;
    }
}
