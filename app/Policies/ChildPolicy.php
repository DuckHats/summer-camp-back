<?php

namespace App\Policies;

use App\Models\User;

class ChildPolicy
{
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
    public function view(User $user, $child): bool
    {
        return $user->isAdmin() || $user->id === $child->parent_id;
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
