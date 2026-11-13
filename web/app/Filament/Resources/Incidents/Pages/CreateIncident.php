<?php

namespace App\Filament\Resources\Incidents\Pages;

use App\Enums\IncidentStatus;
use App\Filament\Resources\Incidents\IncidentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncident extends CreateRecord
{
    protected static string $resource = IncidentResource::class;

    /**
     * The requester and the initial status are never taken from the form.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['requester_id'] = auth()->id();
        $data['status'] = IncidentStatus::Open;

        return $data;
    }
}
