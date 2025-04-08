<?php

namespace App\Policies;

use App\Models\User;

class ErrorPolicy
{
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
    public function view(User $user): bool
    {
        return $user->isAdmin();
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
