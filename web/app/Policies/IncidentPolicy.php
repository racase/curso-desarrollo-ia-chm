<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class IncidentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Incident $incident): bool
    {
        return $user->role !== UserRole::Requester
            || $incident->requester_id === $user->getKey();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::Requester;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Incident $incident): bool
    {
        return false;
    }

    public function assignTechnician(User $user, Incident $incident): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function changeStatus(User $user, Incident $incident): bool
    {
        return $user->hasRole(UserRole::Admin)
            || ($user->hasRole(UserRole::Technician)
                && $incident->assigned_technician_id === $user->getKey());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Incident $incident): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Incident $incident): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Incident $incident): bool
    {
        return false;
    }

    /**
     * @param  Builder<Incident>  $query
     * @return Builder<Incident>
     */
    public function scopeQuery(User $user, Builder $query): Builder
    {
        if ($user->role === UserRole::Requester) {
            return $query->whereBelongsTo($user, 'requester');
        }

        return $query;
    }
}
