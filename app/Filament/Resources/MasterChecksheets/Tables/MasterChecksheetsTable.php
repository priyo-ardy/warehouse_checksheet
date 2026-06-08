<?php

namespace App\Filament\Resources\MasterChecksheets\Tables;

use App\Filament\Exports\MasterChecksheetExporter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MasterChecksheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('equipment_category')
                    ->label('Equipment Category')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->formatStateUsing(fn($state) => ucwords(str_replace('_', ' ', $state))),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Disabled')
                    ->color(fn($state) => $state ? 'success' : 'gray')
                    ->alignCenter(),
                TextColumn::make('remark')
                    ->label('Remark')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('details_count')
                    ->label('Total Item')
                    ->counts('details')
                    ->sortable()
                    ->searchable()
                    ->suffix(' Items')
                    ->alignCenter()
            ])
            ->filters([
                TrashedFilter::make()
                // TrashedFilter::make(),
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                Action::make('refresh')
                    ->label('Refresh')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->tooltip('refresh')
                    ->action(fn() => null),
                ExportAction::make('export')
                    ->label('Export')
                    ->tooltip('Export')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->button()
                    ->exporter(MasterChecksheetExporter::class)
            ]);
    }
}
