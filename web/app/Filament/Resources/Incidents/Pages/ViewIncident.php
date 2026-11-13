<?php

namespace App\Filament\Resources\Incidents\Pages;

use App\Enums\IncidentStatus;
use App\Enums\UserRole;
use App\Exceptions\InvalidIncidentTransition;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Models\Incident;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewIncident extends ViewRecord
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->commentAction(),
            $this->assignAction(),
            $this->changeStatusAction(),
        ];
    }

    private function commentAction(): Action
    {
        return Action::make('comment')
            ->label('Comentar')
            ->icon('heroicon-o-chat-bubble-left')
            ->visible(fn (Incident $record): bool => auth()->user()->can('comment', $record))
            ->schema([
                Textarea::make('body')
                    ->label('Comentario')
                    ->required()
                    ->maxLength(2000)
                    ->rows(4),
            ])
            ->action(function (array $data, Incident $record): void {
                $record->comments()->create([
                    'user_id' => auth()->id(),
                    'body' => $data['body'],
                ]);
                $record->refresh();

                Notification::make()
                    ->title('Comentario añadido')
                    ->success()
                    ->send();
            });
    }

    private function assignAction(): Action
    {
        return Action::make('assign')
            ->label('Asignar')
            ->icon('heroicon-o-user-plus')
            ->visible(fn (Incident $record): bool => auth()->user()->can('assign', $record))
            ->fillForm(fn (Incident $record): array => ['technician_id' => $record->technician_id])
            ->schema([
                Select::make('technician_id')
                    ->label('Técnico')
                    ->options(fn (): array => User::where('role', UserRole::Technician)->orderBy('name')->pluck('name', 'id')->all())
                    ->required(),
            ])
            ->action(function (array $data, Incident $record): void {
                $record->assignTo(User::findOrFail($data['technician_id']));
                $record->refresh();

                Notification::make()
                    ->title('Incidencia asignada a '.$record->technician->name)
                    ->success()
                    ->send();
            });
    }

    private function changeStatusAction(): Action
    {
        return Action::make('changeStatus')
            ->label('Cambiar estado')
            ->icon('heroicon-o-arrow-path')
            ->visible(fn (Incident $record): bool => auth()->user()->can('changeStatus', $record))
            ->schema([
                Select::make('status')
                    ->label('Nuevo estado')
                    ->options(fn (Incident $record): array => collect($record->availableStatusTransitions())
                        ->mapWithKeys(fn (IncidentStatus $status) => [$status->value => $status->getLabel()])
                        ->all())
                    ->helperText(fn (Incident $record): ?string => $record->technician_id === null
                        ? 'Para ponerla en curso, primero asigna un técnico.'
                        : null)
                    ->required(),
            ])
            ->action(function (array $data, Incident $record, Action $action): void {
                try {
                    $record->changeStatus(IncidentStatus::from($data['status']), auth()->user());
                } catch (InvalidIncidentTransition $exception) {
                    Notification::make()
                        ->title($exception->getMessage())
                        ->danger()
                        ->send();

                    $action->halt();
                }

                $record->refresh();

                Notification::make()
                    ->title('Estado cambiado a «'.$record->status->getLabel().'»')
                    ->success()
                    ->send();
            });
    }
}
