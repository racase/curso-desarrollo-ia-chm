<?php

namespace App\Filament\Resources\Incidents\Schemas;

use App\Enums\IncidentPriority;
use App\Models\Asset;
use App\Rules\AvailableAsset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

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
                Select::make('asset_id')
                    ->label('Activo')
                    ->relationship(
                        name: 'asset',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query
                            ->withoutTrashed()
                            ->whereHas(
                                'site',
                                fn (Builder $siteQuery): Builder => $siteQuery->whereNull('sites.deleted_at'),
                            )
                            ->with('site')
                            ->orderBy('code'),
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (Asset $record): string => "{$record->code} · {$record->name} · {$record->site->name}",
                    )
                    ->rules([new AvailableAsset])
                    ->searchable(['code', 'name'])
                    ->preload()
                    ->native(false)
                    ->required(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
            ]);
    }
}
