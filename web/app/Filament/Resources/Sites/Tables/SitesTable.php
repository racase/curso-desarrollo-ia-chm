<?php

namespace App\Filament\Resources\Sites\Tables;

use App\Models\Site;
use App\Models\User;
use App\Services\CatalogManagementService;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class SitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label('Dirección')
                    ->placeholder('Sin dirección')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar'),
                DeleteAction::make()
                    ->label('Eliminar')
                    ->using(function (Site $record): bool {
                        $actor = Filament::auth()->user();

                        abort_unless($actor instanceof User, 403);

                        try {
                            app(CatalogManagementService::class)->deleteSite($actor, $record);
                        } catch (ValidationException $exception) {
                            Notification::make()
                                ->danger()
                                ->title('No se puede eliminar la sede')
                                ->body($exception->errors()['delete'][0])
                                ->send();

                            return false;
                        }

                        return true;
                    })
                    ->failureNotification(null)
                    ->successNotificationTitle('Sede eliminada'),
            ])
            ->toolbarActions([]);
    }
}
