<?php

namespace App\Filament\Resources\Incidents\Tables;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (IncidentStatus $state): string => $state->getLabel())
                    ->badge(),
                TextColumn::make('priority')
                    ->label('Prioridad')
                    ->formatStateUsing(fn (IncidentPriority $state): string => $state->getLabel())
                    ->badge(),
                TextColumn::make('requester.name')
                    ->label('Solicitante'),
                TextColumn::make('asset.code')
                    ->label('Código del activo')
                    ->placeholder('Sin activo'),
                TextColumn::make('asset.name')
                    ->label('Activo')
                    ->placeholder('Sin activo'),
                TextColumn::make('asset.site.name')
                    ->label('Sede')
                    ->placeholder('Sin activo'),
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(IncidentStatus::class),
                SelectFilter::make('priority')
                    ->label('Prioridad')
                    ->options(IncidentPriority::class),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Ver'),
            ]);
    }
}
