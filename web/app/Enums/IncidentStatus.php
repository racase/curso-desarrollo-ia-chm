<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum IncidentStatus: string implements HasColor, HasLabel
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => 'Abierta',
            self::InProgress => 'En curso',
            self::Resolved => 'Resuelta',
            self::Closed => 'Cerrada',
        };
    }

    /**
     * Statuses an incident in this status may move to. Closed is final.
     *
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Open => [self::InProgress],
            self::InProgress => [self::Open, self::Resolved],
            self::Resolved => [self::InProgress, self::Closed],
            self::Closed => [],
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Open => 'info',
            self::InProgress => 'warning',
            self::Resolved => 'success',
            self::Closed => 'gray',
        };
    }
}
