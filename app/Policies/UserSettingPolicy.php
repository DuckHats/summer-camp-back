<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserSetting;

class UserSettingPolicy
{
    public function create(User $user, UserSetting $userSetting): bool
    {
        return $user->id == $userSetting->user_id;
    }

    public function update(User $user, UserSetting $userSetting): bool
    {
        return $user->id == $userSetting->user_id;
    }

    public function delete(User $user, UserSetting $userSetting): bool
    {
        return $user->id == $userSetting->user_id;
    }
}
