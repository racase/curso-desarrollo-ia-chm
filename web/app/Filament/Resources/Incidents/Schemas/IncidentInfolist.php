<?php

namespace App\Filament\Resources\Incidents\Schemas;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use Filament\Infolists\Components\RepeatableEntry;
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
                TextEntry::make('asset.code')
                    ->label('Código del activo')
                    ->placeholder('Sin activo'),
                TextEntry::make('asset.name')
                    ->label('Activo')
                    ->placeholder('Sin activo'),
                TextEntry::make('asset.site.name')
                    ->label('Sede')
                    ->placeholder('Sin activo'),
                TextEntry::make('assignedTechnician.name')
                    ->label('Técnico responsable')
                    ->placeholder('Sin asignar'),
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
                RepeatableEntry::make('statusChanges')
                    ->label('Historial de estados')
                    ->schema([
                        TextEntry::make('from_status')
                            ->label('Estado anterior')
                            ->formatStateUsing(fn (IncidentStatus $state): string => $state->getLabel()),
                        TextEntry::make('to_status')
                            ->label('Estado nuevo')
                            ->formatStateUsing(fn (IncidentStatus $state): string => $state->getLabel()),
                        TextEntry::make('changedBy.name')
                            ->label('Modificado por'),
                        TextEntry::make('created_at')
                            ->label('Fecha')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                RepeatableEntry::make('comments')
                    ->label('Conversación')
                    ->schema([
                        TextEntry::make('content')
                            ->label('Comentario')
                            ->columnSpanFull(),
                        TextEntry::make('author.name')
                            ->label('Autor'),
                        TextEntry::make('created_at')
                            ->label('Publicado')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
