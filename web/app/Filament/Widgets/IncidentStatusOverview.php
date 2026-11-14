<?php

namespace App\Filament\Widgets;

use App\Enums\IncidentStatus;
use App\Models\Incident;
use App\Models\User;
use App\Policies\IncidentPolicy;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IncidentStatusOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Incidencias por estado';

    protected ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return Filament::auth()->user() instanceof User;
    }

    protected function getStats(): array
    {
        $user = Filament::auth()->user();

        if (! $user instanceof User) {
            return [];
        }

        $statuses = [
            IncidentStatus::Open,
            IncidentStatus::InProgress,
            IncidentStatus::Resolved,
        ];
        $statusValues = array_map(
            static fn (IncidentStatus $status): string => $status->value,
            $statuses,
        );
        $counts = app(IncidentPolicy::class)
            ->scopeQuery($user, Incident::query())
            ->whereIn('status', $statusValues)
            ->selectRaw('status, COUNT(*) AS incident_count')
            ->groupBy('status')
            ->toBase()
            ->pluck('incident_count', 'status');

        return array_map(
            static fn (IncidentStatus $status): Stat => Stat::make(
                $status->getLabel(),
                (int) $counts->get($status->value, 0),
            ),
            $statuses,
        );
    }
}
