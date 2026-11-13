<?php

namespace App\Filament\Resources\Assets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class AssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, callable $get) => $rule->where('site_id', $get('site_id')),
                    ),
                Select::make('site_id')
                    ->label('Sede')
                    ->relationship('site', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
