<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Asset;
use App\Models\User;

/**
 * Only admins maintain the catalogue. Nothing is ever deleted.
 */
class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function view(User $user, Asset $model): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function update(User $user, Asset $model): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function delete(User $user, Asset $model): bool
    {
        return false;
    }
}
