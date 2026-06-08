<?php

namespace App\Filament\Resources\Checksheets\Schemas;

use App\Models\Equipment;
use App\Models\MasterChecksheetDetail;
use App\Models\MasterChecksheetHeader;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ChecksheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('code')
                            ->label('Code')
                            ->readOnly()
                            ->placeholder('Automatic generate after save')
                            ->columnSpan(2),
                        DatePicker::make('tanggal')
                            ->label('Date')
                            ->required()
                            ->readOnly()
                            ->default(now())
                            ->columnSpan(2),
                        Select::make('equipment_id')
                            ->label('Equipment')
                            ->required()
                            ->searchPrompt('Type equpment name here ...')
                            ->relationship('equipment', 'name', modifyQueryUsing: fn($query) => $query->where('is_active', true)->orderBy('name', 'asc'))
                            ->searchable()
                            ->native(false)
                            ->preload()
                            ->columnSpan(4)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                if (! $state) {
                                    $set('details', []);
                                    return;
                                }

                                $equipment = Equipment::where('id', $state)->first();

                                if ($equipment) {
                                    $set('leader_id', $equipment->leader_id);

                                    $checksheet = MasterChecksheetHeader::where('equipment_category', $equipment->equipment_category)->first();

                                    if ($checksheet) {
                                        $checksheetDetails = MasterChecksheetDetail::where('header_id', $checksheet->id)
                                            ->orderBy('order', 'asc')
                                            ->get();

                                        $repeaterItems = $checksheetDetails->map(function ($detail) {
                                            return [
                                                'item_id' => $detail->id,
                                                'order' => $detail->order,
                                                'parameter_name_snapshot' => $detail->name, // Sesuai field name di schema baru lu
                                                'status' => null, // Default langsung OK biar gak pegel klik
                                                'actual_value' => null,
                                                'remark' => null
                                            ];
                                        })->toArray();

                                        // --- TETEP DI-PUSH DI SINI BIAR KAMPUNG REPEATER-NYA KEISI ---
                                        $set('details', $repeaterItems);
                                    }
                                }
                            }),
                        Select::make('leader_id')
                            ->label('P.I.C')
                            ->required()
                            ->relationship('leader', 'name', modifyQueryUsing: fn($query) => $query->where('is_active', true)->orderBy('name', 'asc'))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpan(4),
                    ])
                    ->columns(12)
                    ->columnSpanFull(),
                Repeater::make('details')
                    ->relationship('details')
                    ->table([
                        TableColumn::make('No.')->width('5%'),
                        TableColumn::make('Checksheet Item')->width('60%'),
                        TableColumn::make('Status')->alignCenter()->width('35%'),
                    ])
                    ->compact()
                    ->schema([
                        Hidden::make('item_id'),

                        TextInput::make('order')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('parameter_name_snapshot')
                            ->readOnly(),

                        // --- CEK LINE 110 LU DI SINI ---
                        ToggleButtons::make('status')
                            ->options([
                                'OK' => 'OK',
                                'NG' => 'NG',
                                'NA' => 'NA',
                            ])
                            ->colors([
                                'OK' => 'success',
                                'NG' => 'danger',
                                'NA' => 'warning',
                            ])
                            ->icons([
                                'OK' => 'heroicon-m-check-circle',
                                'NG' => 'heroicon-m-x-circle',
                                'NA' => 'heroicon-m-minus-circle',
                            ])
                            ->inline()
                            ->live()
                            ->required()
                            ->extraAttributes([
                                'class' => '[&_div]:!justify-center [&_.flex]:!justify-center mx-auto',
                            ]),
                        TextInput::make('remark')->placeholder('Write remark here')
                    ])
                    ->columnSpanFull()
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->orderColumn('order')
                    ->defaultItems(false),
                Section::make()
                    ->schema([
                        Textarea::make('remark')
                            ->label('Remark')
                            ->placeholder('Kerusakan / abnormality pada unit')
                            ->columnSpanFull()
                            ->required(fn(Get $get) => collect($get('details') ?? [])->contains('status', 'NG'))
                            ->rules([
                                fn(Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $hasNg = collect($get('details') ?? [])->contains('status', 'NG');

                                    if ($hasNg && blank($value)) {
                                        $fail('Please describe the actual condition here ...');
                                    }
                                },
                            ]),
                    ])
                    ->columns(12)
                    ->columnSpanFull()
            ]);
    }
}
