<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Site;
use App\Models\User;

/**
 * Only admins maintain the catalogue. Nothing is ever deleted.
 */
class SitePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function view(User $user, Site $model): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function update(User $user, Site $model): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function delete(User $user, Site $model): bool
    {
        return false;
    }
}
