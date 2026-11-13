<?php

namespace App\Models;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use App\Enums\UserRole;
use App\Exceptions\InvalidIncidentTransition;
use Database\Factories\IncidentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

#[Fillable(['title', 'description', 'priority', 'status', 'requester_id', 'technician_id', 'asset_id'])]
class Incident extends Model
{
    /** @use HasFactory<IncidentFactory> */
    use HasFactory;

    protected $attributes = [
        'status' => 'open',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * The affected asset. Incidents created before assets existed have none.
     *
     * @return BelongsTo<Asset, $this>
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Status history, most recent first.
     *
     * @return HasMany<IncidentStatusChange, $this>
     */
    public function statusChanges(): HasMany
    {
        return $this->hasMany(IncidentStatusChange::class)->latest()->latest('id');
    }

    /**
     * Comments, oldest first.
     *
     * @return HasMany<IncidentComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(IncidentComment::class)->oldest()->oldest('id');
    }

    /**
     * Incidents the given user is allowed to see: requesters only see their
     * own, technicians and admins see all of them.
     *
     * @param  Builder<Incident>  $query
     */
    public function scopeVisibleTo(Builder $query, User $user): void
    {
        if ($user->hasRole(UserRole::Requester)) {
            $query->whereBelongsTo($user, 'requester');
        }
    }

    public function assignTo(User $technician): void
    {
        if (! $technician->hasRole(UserRole::Technician)) {
            throw new InvalidArgumentException('Solo se puede asignar una incidencia a un técnico.');
        }

        $this->technician()->associate($technician)->save();
    }

    /**
     * Statuses this incident can move to right now.
     *
     * @return list<IncidentStatus>
     */
    public function availableStatusTransitions(): array
    {
        return array_values(array_filter(
            $this->status->allowedTransitions(),
            fn (IncidentStatus $status) => $status !== IncidentStatus::InProgress || $this->technician_id !== null,
        ));
    }

    /**
     * Move the incident to a new status and record the change in its history.
     *
     * @throws InvalidIncidentTransition
     */
    public function changeStatus(IncidentStatus $to, User $by): void
    {
        $from = $this->status;

        if (! $from->canTransitionTo($to)) {
            throw InvalidIncidentTransition::notAllowed($from, $to);
        }

        if ($to === IncidentStatus::InProgress && $this->technician_id === null) {
            throw InvalidIncidentTransition::missingTechnician();
        }

        DB::transaction(function () use ($from, $to, $by) {
            $this->update(['status' => $to]);

            $this->statusChanges()->create([
                'user_id' => $by->id,
                'from_status' => $from,
                'to_status' => $to,
            ]);
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => IncidentPriority::class,
            'status' => IncidentStatus::class,
        ];
    }
}
