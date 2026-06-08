<?php

namespace App\Filament\Resources\Equipments\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EquipmentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('Equimpent Image')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->alignCenter()
                            ->hiddenLabel()
                            ->visibility('public')
                            ->disk('public')
                            ->directory('equipment-image')
                            ->removeUploadedFileButtonPosition('right')
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, Get $get) {
                                $nama_equipment = strtolower($get('name')) ?? 'equipment';

                                $slugName = Str::slug($nama_equipment);

                                return (string) str($slugName . '-' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension());
                            })
                    ])
                    ->columnSpan(3),
                Section::make()
                    ->schema([
                        Select::make('equipment_category')
                            ->label('Category')
                            ->options([
                                'forklift' => 'Forklift',
                                'palet_mover' => 'Pallet Mover',
                                'stacker' => 'Stacker',
                                'truck' => 'Trucking'
                            ])
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->searchPrompt('Type equipment category')
                            ->columnSpan(3),
                        TextInput::make('name')
                            ->label('Equipment Name')
                            ->unique(ignoreRecord: true)
                            ->maxLength(150)
                            ->placeholder('Equipment name')
                            ->required()
                            ->autocomplete(false)
                            ->columnSpan(3)
                            ->autofocus(),
                        TextInput::make('brand')
                            ->label('Brand')
                            ->maxLength(150)
                            ->placeholder('Brand')
                            ->nullable()
                            ->autocomplete(false)
                            ->columnSpan(2),
                        Select::make('leader_id')
                            ->label('Lead Coordinator')
                            ->relationship('leaders', 'name', modifyQueryUsing: fn($query) => $query->where('is_active', true)->orderBy('name', 'asc'))
                            ->searchable()
                            ->native(false)
                            ->preload()
                            ->columnSpan(3),
                        TextInput::make('serial_no')
                            ->label('Serial No.')
                            ->unique(ignoreRecord: true)
                            ->nullable()
                            ->placeholder('Serial No')
                            ->maxLength(150)
                            ->autocomplete(false)
                            ->columnSpan(4),
                        Textarea::make('remark')
                            ->label('Remark')
                            ->placeholder('Write some information here ...')
                            ->columnSpan(8)
                            ->nullable()
                    ])
                    ->columns(12)
                    ->columnSpan(9)
            ])->columns(12);
    }
}
