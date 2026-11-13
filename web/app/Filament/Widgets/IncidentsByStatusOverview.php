<?php

namespace App\Filament\Widgets;

use App\Enums\IncidentStatus;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Models\Incident;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IncidentsByStatusOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Incidencias por estado';

    /**
     * One stat per status, counting only the incidents the user can see.
     */
    protected function getStats(): array
    {
        $counts = Incident::query()
            ->visibleTo(auth()->user())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return array_map(
            fn (IncidentStatus $status): Stat => Stat::make($status->getLabel(), (int) ($counts[$status->value] ?? 0))
                ->color($status->getColor())
                ->url(IncidentResource::getUrl('index', [
                    'filters' => ['status' => ['value' => $status->value]],
                ])),
            IncidentStatus::cases(),
        );
    }
}
