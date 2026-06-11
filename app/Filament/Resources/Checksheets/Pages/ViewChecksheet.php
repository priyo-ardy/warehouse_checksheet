<?php

namespace App\Filament\Resources\Checksheets\Pages;

use App\Filament\Resources\Checksheets\ChecksheetResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewChecksheet extends ViewRecord
{
    protected static string $resource = ChecksheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to list')
                ->tooltip('Back to list')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
            EditAction::make()
                ->label('Edit')
                ->tooltip('Edit')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->visible(fn($record) => $record->leader_approve === false && $record->doc_status === null),
        ];
    }
}
