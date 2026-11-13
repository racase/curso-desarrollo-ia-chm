<?php

namespace App\Filament\Resources\Incidents\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IncidentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Título')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->label('Estado')
                    ->badge(),
                TextEntry::make('priority')
                    ->label('Prioridad')
                    ->badge(),
                TextEntry::make('asset.name')
                    ->label('Activo')
                    ->placeholder('Sin activo'),
                TextEntry::make('asset.site.name')
                    ->label('Sede')
                    ->placeholder('Sin activo'),
                TextEntry::make('requester.name')
                    ->label('Solicitante'),
                TextEntry::make('technician.name')
                    ->label('Técnico asignado')
                    ->placeholder('Sin asignar'),
                TextEntry::make('created_at')
                    ->label('Fecha de alta')
                    ->dateTime('d/m/Y H:i'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->placeholder('Sin descripción')
                    ->columnSpanFull(),
                Section::make('Comentarios')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('comments')
                            ->hiddenLabel()
                            ->placeholder('Todavía no hay comentarios.')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('author.name')
                                    ->label('Autor'),
                                TextEntry::make('created_at')
                                    ->label('Fecha')
                                    ->dateTime('d/m/Y H:i'),
                                TextEntry::make('body')
                                    ->hiddenLabel()
                                    ->columnSpanFull(),
                            ]),
                    ]),
                Section::make('Historial de estados')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('statusChanges')
                            ->hiddenLabel()
                            ->placeholder('Todavía no ha cambiado de estado.')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('from_status')
                                    ->label('Estado anterior')
                                    ->badge(),
                                TextEntry::make('to_status')
                                    ->label('Estado nuevo')
                                    ->badge(),
                                TextEntry::make('user.name')
                                    ->label('Cambiado por'),
                                TextEntry::make('created_at')
                                    ->label('Fecha')
                                    ->dateTime('d/m/Y H:i'),
                            ]),
                    ]),
            ]);
    }
}
