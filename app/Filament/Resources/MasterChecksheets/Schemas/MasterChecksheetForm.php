<?php

namespace App\Filament\Resources\MasterChecksheets\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MasterChecksheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('equipment_category')
                            ->label('Equipment')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->options([
                                'forklift' => 'Forklift',
                                'palet_mover' => 'Pallet Mover',
                                'stacker' => 'Stacker',
                                'truck' => 'Trucking'
                            ])
                            ->native(false)
                            ->searchable()
                            ->columnSpan(3),
                        Textarea::make('remark')
                            ->label('Remark')
                            ->nullable()
                            ->placeholder('Write some information here ...')
                            ->columnSpan(9)
                    ])
                    ->columns(12)
                    ->columnSpanFull(),
                Repeater::make('details')
                    ->hiddenLabel()
                    ->relationship('details')
                    ->table([
                        TableColumn::make('Checksheet List')->alignLeft()
                    ])
                    ->compact()
                    ->schema([
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->maxLength(150)
                            ->required()
                            ->placeholder('Write checksheet items here ...')
                            ->dehydrateStateUsing(fn(?string $state): ?string => $state ? ucfirst(mb_strtolower(trim($state))) : null)
                            ->rule('distinct')
                    ])
                    ->columns(12)
                    ->columnSpanFull()
                    ->orderColumn('order')
                    ->itemNumbers()
            ])
            ->columns(12);
    }
}
