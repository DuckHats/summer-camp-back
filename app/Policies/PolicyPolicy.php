<?php

namespace App\Policies;

use App\Models\Policy;
use App\Models\User;

class PolicyPolicy
{
    public function create(User $user, Policy $policy): bool
    {
        return $user->id == $policy->user_id;
    }

    public function update(User $user, Policy $policy): bool
    {
        return $user->id == $policy->user_id;
    }

    public function delete(User $user, Policy $policy): bool
    {
        return $user->id == $policy->user_id;
    }
}
