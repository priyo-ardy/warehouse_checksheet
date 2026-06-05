<?php

namespace App\Filament\Resources\Equipments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EquipmentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Equipment Name')
                            ->unique(ignoreRecord: true)
                            ->maxLength(150)
                            ->placeholder('Equipment name')
                            ->required()
                            ->autocomplete(false)
                            ->columnSpan(4)
                            ->autofocus(),
                        TextInput::make('brand')
                            ->label('Brand')
                            ->maxLength(150)
                            ->placeholder('Brand')
                            ->nullable()
                            ->autocomplete(false)
                            ->columnSpan(3),
                        TextInput::make('serial_no')
                            ->label('Serial No.')
                            ->unique(ignoreRecord: true)
                            ->nullable()
                            ->placeholder('Serial No')
                            ->maxLength(150)
                            ->autocomplete(false)
                            ->columnSpan(5)
                    ])
                    ->columns(12)
                    ->columnSpanFull()
            ]);
    }
}
