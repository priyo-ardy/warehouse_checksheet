<?php

namespace App\Filament\Resources\Approvals\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document.code')->label('Checksheet No'),
                TextColumn::make('document.tanggal')->label('Date'),
                TextColumn::make('document.equipment.equipment_category')->label('Date'),
                TextColumn::make('document.equipment.name')->label('Equipment Name'),
                TextColumn::make('document.leader.name')->label('Lead Coordinator'),
                TextColumn::make('document.creator.name')->label('Created By'),
                TextColumn::make('document.remark')->label('Remark')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
