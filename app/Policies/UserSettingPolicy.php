<?php

namespace App\Policies;

use App\Models\User;

class UserSettingPolicy
{
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
    public function view(User $user, User $settingUser): bool
    {
        return $user == $user || $user->isAdmin();
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
