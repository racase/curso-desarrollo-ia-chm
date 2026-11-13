<?php

namespace App\Filament\Resources\Incidents\Tables;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use App\Models\Site;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                TextColumn::make('priority')
                    ->label('Prioridad')
                    ->badge(),
                TextColumn::make('asset.name')
                    ->label('Activo')
                    ->placeholder('Sin activo'),
                TextColumn::make('asset.site.name')
                    ->label('Sede')
                    ->placeholder('Sin activo'),
                TextColumn::make('requester.name')
                    ->label('Solicitante'),
                TextColumn::make('technician.name')
                    ->label('Técnico asignado')
                    ->placeholder('Sin asignar'),
                TextColumn::make('created_at')
                    ->label('Fecha de alta')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(IncidentStatus::class),
                SelectFilter::make('priority')
                    ->label('Prioridad')
                    ->options(IncidentPriority::class),
                SelectFilter::make('site')
                    ->label('Sede')
                    ->options(fn (): array => Site::orderBy('name')->pluck('name', 'id')->all())
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, $siteId) => $query->whereRelation('asset', 'site_id', $siteId),
                    )),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
