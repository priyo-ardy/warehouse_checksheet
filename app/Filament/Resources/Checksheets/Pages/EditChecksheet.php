<?php

namespace App\Filament\Resources\Checksheets\Pages;

use App\Filament\Resources\Checksheets\ChecksheetResource;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditChecksheet extends EditRecord
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
            ViewAction::make()->label('Cancel')->tooltip('Cancel')->icon(Heroicon::OutlinedArrowUturnLeft),
        ];
    }
}
