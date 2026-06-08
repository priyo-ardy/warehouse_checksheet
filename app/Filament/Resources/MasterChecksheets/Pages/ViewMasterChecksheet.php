<?php

namespace App\Filament\Resources\MasterChecksheets\Pages;

use App\Filament\Resources\MasterChecksheets\MasterChecksheetResource;
use App\Models\MasterChecksheetHeader;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewMasterChecksheet extends ViewRecord
{
    protected static string $resource = MasterChecksheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to List')
                ->tooltip('Back to list')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->url(static::getResource()::getUrl('index')),
            Action::make('create')
                ->label('New')
                ->tooltip('New')
                ->icon(Heroicon::OutlinedPlusCircle)
                ->color('success')
                ->url(static::getResource()::getUrl('create')),
            EditAction::make()
                ->label('Edit')
                ->tooltip('Edit')
                ->icon(Heroicon::OutlinedPencilSquare),
            ActionGroup::make([
                DeleteAction::make()->icon(Heroicon::OutlinedTrash)->tooltip('Delete'),
                ForceDeleteAction::make()->tooltip('Force Delete'),
                RestoreAction::make()->label('Restore'),
            ])
                ->label('Action')
                ->icon(Heroicon::OutlinedEllipsisVertical)
                ->button()
                ->color('gray'),
            ActionGroup::make([
                Action::make('first')
                    ->label('First')
                    ->tooltip('Go to first data')
                    ->icon(Heroicon::OutlinedChevronDoubleLeft)
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof MasterChecksheetHeader) {
                            return null;
                        }

                        $firstRecord = MasterChecksheetHeader::orderBy('id', 'asc')->first();

                        return ($firstRecord && $firstRecord->id !== $currentRecord->id)
                            ? MasterChecksheetResource::getUrl('view', ['record' => $firstRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof MasterChecksheetHeader) {
                            return true;
                        }

                        return ! MasterChecksheetHeader::where('id', '<', $currentRecord->id)->exists();
                    }),
                Action::make('prev')
                    ->label('Previous')
                    ->tooltip('Go to previous data')
                    ->icon(Heroicon::OutlinedChevronLeft)
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof MasterChecksheetHeader) {
                            return null;
                        }

                        $prevRecord = MasterChecksheetHeader::where('id', '<', $currentRecord->id)
                            ->orderBy('id', 'desc')
                            ->first();

                        return ($prevRecord && $prevRecord->id !== $currentRecord->id)
                            ? MasterChecksheetResource::getUrl('view', ['record' => $prevRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof MasterChecksheetHeader) {
                            return true;
                        }

                        return ! MasterChecksheetHeader::where('id', '<', $currentRecord->id)->exists();
                    }),
                Action::make('next')
                    ->label('Next')
                    ->tooltip('Go to next data')
                    ->icon(Heroicon::OutlinedChevronRight)
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof MasterChecksheetHeader) {
                            return null;
                        }

                        $nextRecord = MasterChecksheetHeader::where('id', '>', $currentRecord->id)
                            ->orderBy('id', 'asc')
                            ->first();

                        return ($nextRecord && $nextRecord->id !== $currentRecord->id)
                            ? MasterChecksheetResource::getUrl('view', ['record' => $nextRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof MasterChecksheetHeader) {
                            return true;
                        }

                        return ! MasterChecksheetHeader::where('id', '>', $currentRecord->id)->exists();
                    }),
                Action::make('last')
                    ->label('Last')
                    ->tooltip('Go to last data')
                    ->icon(Heroicon::OutlinedChevronDoubleRight)
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof MasterChecksheetHeader) {
                            return null;
                        }

                        $lastRecord = MasterChecksheetHeader::orderBy('id', 'desc')
                            ->first();

                        return ($lastRecord && $lastRecord->id !== $currentRecord->id)
                            ? MasterChecksheetResource::getUrl('view', ['record' => $lastRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof MasterChecksheetHeader) {
                            return true;
                        }

                        return ! MasterChecksheetHeader::where('id', '>', $currentRecord->id)->exists();
                    }),
            ])
                ->label('Navigation')
                ->color('gray')
                ->button()
                ->icon(Heroicon::OutlinedEllipsisVertical)
        ];
    }
}
