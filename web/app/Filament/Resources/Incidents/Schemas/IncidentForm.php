<?php

namespace App\Filament\Resources\Incidents\Schemas;

use App\Enums\IncidentPriority;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IncidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                Select::make('priority')
                    ->label('Prioridad')
                    ->options(IncidentPriority::class)
                    ->required(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
            ]);
    }
}
