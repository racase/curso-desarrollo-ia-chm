<?php

namespace App\Models;

use App\Enums\IncidentStatus;
use Database\Factories\IncidentStatusChangeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['incident_id', 'user_id', 'from_status', 'to_status'])]
class IncidentStatusChange extends Model
{
    /** @use HasFactory<IncidentStatusChangeFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Incident, $this>
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * The user who made the change.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'from_status' => IncidentStatus::class,
            'to_status' => IncidentStatus::class,
        ];
    }
}
