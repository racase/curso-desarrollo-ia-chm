<?php

namespace App\Policies;

use App\Enums\IncidentStatus;
use App\Enums\UserRole;
use App\Models\Incident;
use App\Models\User;

class IncidentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Incident $incident): bool
    {
        return ! $user->hasRole(UserRole::Requester)
            || $incident->requester_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Title, description and priority are not editable once created.
     */
    public function update(User $user, Incident $incident): bool
    {
        return false;
    }

    public function delete(User $user, Incident $incident): bool
    {
        return false;
    }

    /**
     * Whoever can see the incident can comment on it, unless it is closed.
     */
    public function comment(User $user, Incident $incident): bool
    {
        return $this->view($user, $incident)
            && $incident->status !== IncidentStatus::Closed;
    }

    /**
     * Only admins assign incidents, and never once they are closed.
     */
    public function assign(User $user, Incident $incident): bool
    {
        return $user->hasRole(UserRole::Admin)
            && $incident->status !== IncidentStatus::Closed;
    }

    /**
     * Admins and the assigned technician move the incident through its statuses.
     */
    public function changeStatus(User $user, Incident $incident): bool
    {
        if ($incident->status->allowedTransitions() === []) {
            return false;
        }

        return $user->hasRole(UserRole::Admin)
            || ($user->hasRole(UserRole::Technician) && $incident->technician_id === $user->id);
    }
}
