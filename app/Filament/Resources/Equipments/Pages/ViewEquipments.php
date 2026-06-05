<?php

namespace App\Filament\Resources\Equipments\Pages;

use App\Filament\Resources\Equipments\EquipmentsResource;
use App\Models\Equipment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewEquipments extends ViewRecord
{
    protected static string $resource = EquipmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to List')
                ->tooltip('Back to list')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->url(static::getResource()::getUrl('index'))
                ->color('gray'),
            Action::make('add')
                ->label('New')
                ->tooltip('New')
                ->icon(Heroicon::OutlinedPlusCircle)
                ->color('success')
                ->url(static::getResource()::getUrl('create')),
            EditAction::make()->label('Edit')->tooltip('Edit')->icon(Heroicon::OutlinedPencilSquare),
            ActionGroup::make([
                DeleteAction::make()
                    ->label('Delete')
                    ->tooltip('Delete')
                    ->icon(Heroicon::OutlinedTrash)
                    ->requiresConfirmation(),
                Action::make('first')
                    ->label('First')
                    ->icon(Heroicon::OutlinedChevronDoubleLeft)
                    ->tooltip('Go to first data')
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof Equipment) {
                            return null;
                        }

                        $firstRecord = Equipment::orderBy('id', 'asc')->first();

                        return ($firstRecord && $firstRecord->id !== $currentRecord->id)
                            ? EquipmentsResource::getUrl('view', ['record' => $firstRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof Equipment) {
                            return true;
                        }

                        return ! Equipment::where('id', '<', $currentRecord->id)->exists();
                    }),
                Action::make('prev')
                    ->label('Previous')
                    ->icon(Heroicon::OutlinedChevronLeft)
                    ->tooltip('Go to previous data')
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof Equipment) {
                            return null;
                        }

                        $prevRecord = Equipment::where('id', '<', $currentRecord->id)
                            ->orderBy('id', 'desc')
                            ->first();

                        return ($prevRecord && $prevRecord->id !== $currentRecord->id)
                            ? EquipmentsResource::getUrl('view', ['record' => $prevRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof Equipment) {
                            return true;
                        }

                        return ! Equipment::where('id', '<', $currentRecord->id)->exists();
                    }),
                Action::make('next')
                    ->label('Next')
                    ->icon(Heroicon::OutlinedChevronRight)
                    ->tooltip('Go to next data')
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof Equipment) {
                            return null;
                        }

                        $nextRecord = Equipment::where('id', '>', $currentRecord->id)
                            ->orderBy('id', 'asc')
                            ->first();

                        return ($nextRecord && $nextRecord->id !== $currentRecord->id)
                            ? EquipmentsResource::getUrl('view', ['record' => $nextRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof Equipment) {
                            return true;
                        }

                        return ! Equipment::where('id', '>', $currentRecord->id)->exists();
                    }),
                Action::make('last')
                    ->label('Last')
                    ->icon(Heroicon::OutlinedChevronDoubleRight)
                    ->tooltip('Go to last data')
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof Equipment) {
                            return null;
                        }

                        $lastData = Equipment::orderBy('id', 'desc')->first();

                        return ($lastData && $lastData->id !== $currentRecord->id)
                            ? EquipmentsResource::getUrl('view', ['record' => $lastData->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof Equipment) {
                            return true;
                        }

                        return ! Equipment::where('id', '>', $currentRecord->id)->exists();
                    })
            ])
                ->label('More Action')
                ->icon(Heroicon::OutlinedEllipsisVertical)
                ->button()
                ->color('gray')
        ];
    }
}
