<?php

namespace App\Filament\Resources\Incidents\Schemas;

use App\Enums\IncidentPriority;
use App\Models\Site;
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
                Select::make('asset_id')
                    ->label('Activo')
                    ->options(fn (): array => Site::query()
                        ->with(['assets' => fn ($query) => $query->orderBy('name')])
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn (Site $site) => [$site->name => $site->assets->pluck('name', 'id')->all()])
                        ->all())
                    ->searchable()
                    ->required()
                    ->exists('assets', 'id'),
                Select::make('priority')
                    ->label('Prioridad')
                    ->options(IncidentPriority::class)
                    ->default(IncidentPriority::Medium)
                    ->required(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}
