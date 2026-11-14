<?php

namespace App\Filament\Resources\Assets\Tables;

use App\Models\Asset;
use App\Models\User;
use App\Services\CatalogManagementService;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('site.name')
                    ->label('Sede')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar'),
                DeleteAction::make()
                    ->label('Eliminar')
                    ->using(function (Asset $record): bool {
                        $actor = Filament::auth()->user();

                        abort_unless($actor instanceof User, 403);

                        app(CatalogManagementService::class)->deleteAsset($actor, $record);

                        return true;
                    })
                    ->successNotificationTitle('Activo eliminado'),
            ])
            ->toolbarActions([]);
    }
}
