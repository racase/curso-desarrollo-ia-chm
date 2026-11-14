<?php

namespace App\Services;

use App\Enums\IncidentStatus;
use App\Enums\UserRole;
use App\Models\Incident;
use App\Models\IncidentStatusChange;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class IncidentWorkflow
{
    public function assignTechnician(User $actor, Incident $incident, User $technician): Incident
    {
        Gate::forUser($actor)->authorize('assignTechnician', $incident);

        if (! $technician->hasRole(UserRole::Technician)) {
            throw ValidationException::withMessages([
                'technician_id' => 'El usuario seleccionado debe tener el rol técnico.',
            ]);
        }

        $incident->update(['assigned_technician_id' => $technician->getKey()]);

        return $incident->refresh();
    }

    public function transition(
        User $actor,
        Incident $incident,
        IncidentStatus $targetStatus,
    ): IncidentStatusChange {
        $statusChange = DB::transaction(function () use ($actor, $incident, $targetStatus): IncidentStatusChange {
            $lockedIncident = Incident::query()
                ->lockForUpdate()
                ->findOrFail($incident->getKey());

            Gate::forUser($actor)->authorize('changeStatus', $lockedIncident);

            $fromStatus = $lockedIncident->status;

            if ($fromStatus->next() !== $targetStatus) {
                throw ValidationException::withMessages([
                    'status' => 'Solo se puede avanzar al siguiente estado permitido.',
                ]);
            }

            $lockedIncident->update(['status' => $targetStatus]);

            return $lockedIncident->statusChanges()->create([
                'from_status' => $fromStatus,
                'to_status' => $targetStatus,
                'changed_by_id' => $actor->getKey(),
            ]);
        });

        $incident->refresh();

        return $statusChange;
    }
}
