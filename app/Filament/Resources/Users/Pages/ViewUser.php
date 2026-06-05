<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('back to List')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->tooltip('Back to list')
                ->url(static::getResource()::getUrl('index')),
            Action::make('add')
                ->label('New')
                ->icon(Heroicon::OutlinedPlusCircle)
                ->color('success')
                ->tooltip('Register new user')
                ->url(static::getResource()::getUrl('create')),
            EditAction::make()->label('Edit')->icon(Heroicon::OutlinedPencilSquare)->tooltip('Edit'),
            ActionGroup::make([
                DeleteAction::make()
                    ->label('Delete')
                    ->tooltip('Delete')
                    ->requiresConfirmation()
                    ->icon(Heroicon::OutlinedTrash),
                ForceDeleteAction::make()
                    ->label('Force Delete')
                    ->icon(Heroicon::OutlinedTrash)
                    ->tooltip('Force Delete')
                    ->requiresConfirmation(),
                Action::make('first')
                    ->label('First')
                    ->icon(Heroicon::OutlinedChevronDoubleLeft)
                    ->tooltip('Go to first data')
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof User) {
                            return null;
                        }

                        $firstRecord = User::orderBy('id', 'asc')->first();

                        return ($firstRecord && $firstRecord->id !== $currentRecord->id)
                            ? UserResource::getUrl('view', ['record' => $firstRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof User) {
                            return true;
                        }

                        return ! User::where('id', '<', $currentRecord->id)->exists();
                    }),
                Action::make('prev')
                    ->label('Previous')
                    ->icon(Heroicon::OutlinedChevronLeft)
                    ->tooltip('Go to previous data')
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof User) {
                            return null;
                        }

                        $prevRecord = User::where('id', '<', $currentRecord->id)
                            ->orderBy('id', 'desc')
                            ->first();

                        return ($prevRecord && $prevRecord->id !== $currentRecord->id)
                            ? UserResource::getUrl('view', ['record' => $prevRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof User) {
                            return true;
                        }

                        return ! User::where('id', '<', $currentRecord->id)->exists();
                    }),
                Action::make('next')
                    ->label('Next')
                    ->icon(Heroicon::OutlinedChevronRight)
                    ->tooltip('Go to next data')
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof User) {
                            return null;
                        }

                        $nextRecord = User::where('id', '>', $currentRecord->id)
                            ->orderBy('id', 'asc')
                            ->first();

                        return ($nextRecord && $nextRecord->id !== $currentRecord->id)
                            ? UserResource::getUrl('view', ['record' => $nextRecord->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof User) {
                            return true;
                        }

                        return ! User::where('id', '>', $currentRecord->id)->exists();
                    }),
                Action::make('last')
                    ->label('Last')
                    ->icon(Heroicon::OutlinedChevronDoubleRight)
                    ->tooltip('Go to last data')
                    ->url(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof User) {
                            return null;
                        }

                        $lastData = User::orderBy('id', 'desc')->first();

                        return ($lastData && $lastData->id !== $currentRecord->id)
                            ? UserResource::getUrl('view', ['record' => $lastData->id])
                            : null;
                    })
                    ->disabled(function () {
                        $currentRecord = $this->record;

                        if (! $currentRecord instanceof User) {
                            return true;
                        }

                        return ! User::where('id', '>', $currentRecord->id)->exists();
                    })
            ])
                ->label('More Actions')
                ->icon(Heroicon::OutlinedEllipsisVertical)
                ->button()
                ->color('gray')
        ];
    }
}
