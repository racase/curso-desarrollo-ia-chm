<?php

namespace App\Filament\Resources\Incidents\Schemas;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IncidentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Título'),
                TextEntry::make('requester.name')
                    ->label('Solicitante'),
                TextEntry::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (IncidentStatus $state): string => $state->getLabel())
                    ->badge(),
                TextEntry::make('priority')
                    ->label('Prioridad')
                    ->formatStateUsing(fn (IncidentPriority $state): string => $state->getLabel())
                    ->badge(),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
