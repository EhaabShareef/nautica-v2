<?php

namespace App\Policies;

use App\Models\AppType;
use App\Models\User;

class AppTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, AppType $type): bool
    {
        return $user->hasRole('admin') && !$type->is_protected;
    }

    public function delete(User $user, AppType $type): bool
    {
        return $user->hasRole('admin') && !$type->is_protected;
    }
}
