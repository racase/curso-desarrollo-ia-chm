<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\UserManagementService;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Rol')
                    ->formatStateUsing(fn (UserRole $state): string => $state->getLabel())
                    ->badge()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar'),
                DeleteAction::make()
                    ->label('Eliminar')
                    ->using(function (User $record): bool {
                        $actor = Filament::auth()->user();

                        abort_unless($actor instanceof User, 403);

                        try {
                            app(UserManagementService::class)->delete($actor, $record);
                        } catch (ValidationException $exception) {
                            Notification::make()
                                ->danger()
                                ->title('No se puede eliminar el usuario')
                                ->body($exception->errors()['delete'][0])
                                ->send();

                            return false;
                        }

                        return true;
                    })
                    ->failureNotification(null)
                    ->successNotificationTitle('Usuario eliminado'),
            ]);
    }
}
