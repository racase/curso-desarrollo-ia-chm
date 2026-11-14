<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum IncidentStatus: string implements HasLabel
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

    public function next(): ?self
    {
        return match ($this) {
            self::Open => self::InProgress,
            self::InProgress => self::Resolved,
            self::Resolved => self::Closed,
            self::Closed => null,
        };
    }
}
