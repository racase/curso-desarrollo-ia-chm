<?php

namespace App\Filament\Resources\Assets\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('site.name')
                    ->label('Sede')
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('site')
                    ->label('Sede')
                    ->relationship('site', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
