<?php

namespace App\Filament\Resources\Equipments\Tables;

use App\Filament\Exports\EquipmentExporter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\QueryBuilder\Constraints\SelectConstraint;
use Filament\QueryBuilder\Constraints\TextConstraint;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EquipmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Equipment Name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('brand')
                    ->label('Brand')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('serial_no')
                    ->label('Equipment Serial No')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('is_active')
                    ->label('Equipment Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Not Active')
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->alignCenter()
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name'),
                        TextConstraint::make('brand'),
                        TextConstraint::make('serial_no'),
                        SelectConstraint::make('is_active')
                            ->options([
                                '0' => 'Not Active',
                                '1' => 'Active'
                            ])
                            ->searchable()
                            ->native(false)
                    ])
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->filtersFormWidth('4xl')
            ->persistFiltersInSession()
            ->filtersTriggerAction(
                fn($action) => $action
                    ->button()
                    ->label('Filter')
                    ->icon('heroicon-o-funnel')
            )
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
                    ->tooltip('Refresh')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->action(fn() => null),
                ExportAction::make('export')
                    ->label('Export')
                    ->tooltip('Export')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->exporter(EquipmentExporter::class)
            ]);
    }
}
