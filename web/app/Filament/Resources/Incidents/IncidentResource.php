<?php

namespace App\Filament\Resources\Incidents;

use App\Filament\Resources\Incidents\Pages\CreateIncident;
use App\Filament\Resources\Incidents\Pages\ListIncidents;
use App\Filament\Resources\Incidents\Pages\ViewIncident;
use App\Filament\Resources\Incidents\Schemas\IncidentForm;
use App\Filament\Resources\Incidents\Schemas\IncidentInfolist;
use App\Filament\Resources\Incidents\Tables\IncidentsTable;
use App\Models\Incident;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IncidentResource extends Resource
{
    protected static ?string $model = Incident::class;

    protected static ?string $modelLabel = 'incidencia';

    protected static ?string $pluralModelLabel = 'incidencias';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    public static function form(Schema $schema): Schema
    {
        return IncidentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IncidentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentsTable::configure($table);
    }

    /**
     * Requesters only reach their own incidents, both in the list and by URL.
     *
     * @return Builder<Incident>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->visibleTo(auth()->user());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncidents::route('/'),
            'create' => CreateIncident::route('/create'),
            'view' => ViewIncident::route('/{record}'),
        ];
    }
}
