<?php

namespace App\Models;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use Database\Factories\IncidentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'status', 'priority', 'requester_id', 'assigned_technician_id'])]
class Incident extends Model
{
    /** @use HasFactory<IncidentFactory> */
    use HasFactory;

    /** @return BelongsTo<User, $this> */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /** @return BelongsTo<User, $this> */
    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    /** @return HasMany<IncidentStatusChange, $this> */
    public function statusChanges(): HasMany
    {
        return $this->hasMany(IncidentStatusChange::class)->oldest();
    }

    /** @return HasMany<IncidentComment, $this> */
    public function comments(): HasMany
    {
        return $this->hasMany(IncidentComment::class)
            ->oldest()
            ->orderBy('id');
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => IncidentStatus::class,
            'priority' => IncidentPriority::class,
        ];
    }
}
