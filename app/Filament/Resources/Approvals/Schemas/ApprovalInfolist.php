<?php

namespace App\Filament\Resources\Approvals\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApprovalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('document.code')
                            ->label('Checksheet No.')
                            ->weight('bold')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 3,
                            ]),

                        TextEntry::make('document.tanggal')
                            ->label('Checksheet Date')
                            ->date("d/M/Y")
                            ->weight('bold')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 3,
                            ]),

                        TextEntry::make('document.equipment.name')
                            ->label('Equipment Name')
                            ->weight('bold')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 3,
                            ]),

                        TextEntry::make('document.leader.name')
                            ->label('Lead Coordinator')
                            ->weight('bold')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 3,
                            ]),
                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 12,
                    ])
                    ->columnSpanFull(),
                RepeatableEntry::make('document.details')
                    ->hiddenLabel()
                    ->table([
                        TableColumn::make('No.')->alignCenter(),
                        TableColumn::make('Checksheet Item')->alignCenter(),
                        TableColumn::make('Status')->alignCenter()
                    ])
                    ->schema([
                        TextEntry::make('order')->alignRight(),
                        TextEntry::make('parameter_name_snapshot'),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn($state) => match (strtolower($state)) {
                                'ok' => 'OK',
                                'ng' => 'NG',
                                'na' => 'Not Applicable',
                                default => $state
                            })
                            ->color(fn($state) => match (strtolower($state)) {
                                'ok' => 'success',
                                'ng' => 'danger',
                                'na' => 'gray'
                            })
                            ->alignCenter()
                    ])
                    ->columns(12)
                    ->columnSpanFull(),
                Section::make()
                    ->schema([
                        TextEntry::make('document.remark')
                            ->label('Remark')
                            ->weight('bold')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12
                            ])
                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 12,
                    ])
                    ->columnSpanFull()
            ]);
    }
}
