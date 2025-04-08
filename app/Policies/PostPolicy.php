<?php

namespace App\Policies;

use App\Models\User;

class PostPolicy
{
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
    public function view(User $user): bool
    {
        // Always return true
        return $user->isAdmin() || $user == $user;
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
