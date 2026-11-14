<?php

namespace App\Filament\Resources\Incidents\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Models\Incident;
use App\Models\User;
use App\Services\IncidentCommentService;
use App\Services\IncidentWorkflow;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Gate;

class ViewIncident extends ViewRecord
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('assignTechnician')
                ->label('Asignar técnico')
                ->icon('heroicon-o-user-plus')
                ->visible(fn (Incident $record): bool => $this->currentUserCan('assignTechnician', $record))
                ->schema([
                    Select::make('technician_id')
                        ->label('Técnico')
                        ->options(fn (): array => User::query()
                            ->where('role', UserRole::Technician)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all())
                        ->required()
                        ->searchable()
                        ->preload(),
                ])
                ->fillForm(fn (Incident $record): array => [
                    'technician_id' => $record->assigned_technician_id,
                ])
                ->action(function (array $data, Incident $record): void {
                    app(IncidentWorkflow::class)->assignTechnician(
                        $this->user(),
                        $record,
                        User::query()->findOrFail($data['technician_id']),
                    );
                }),
            Action::make('advanceStatus')
                ->label(fn (Incident $record): string => $record->status->next() === null
                    ? 'Incidencia cerrada'
                    : 'Avanzar a '.$record->status->next()->getLabel())
                ->icon('heroicon-o-arrow-right')
                ->visible(fn (Incident $record): bool => $record->status->next() !== null
                    && $this->currentUserCan('changeStatus', $record))
                ->requiresConfirmation()
                ->action(function (Incident $record): void {
                    $nextStatus = $record->status->next();

                    if ($nextStatus === null) {
                        return;
                    }

                    app(IncidentWorkflow::class)->transition(
                        $this->user(),
                        $record,
                        $nextStatus,
                    );
                }),
            Action::make('addComment')
                ->label('Añadir comentario')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->visible(fn (Incident $record): bool => $this->currentUserCan('comment', $record))
                ->schema([
                    Textarea::make('content')
                        ->label('Comentario')
                        ->required()
                        ->rows(5),
                ])
                ->action(function (array $data, Incident $record): void {
                    app(IncidentCommentService::class)->publish(
                        $this->user(),
                        $record,
                        $data['content'],
                    );

                    $record->unsetRelation('comments');
                }),
        ];
    }

    private function currentUserCan(string $ability, Incident $incident): bool
    {
        $user = Filament::auth()->user();

        return $user instanceof User
            && Gate::forUser($user)->allows($ability, $incident);
    }

    private function user(): User
    {
        $user = Filament::auth()->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }
}
