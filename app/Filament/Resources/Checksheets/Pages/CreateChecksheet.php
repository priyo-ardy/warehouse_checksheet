<?php

namespace App\Filament\Resources\Checksheets\Pages;

use App\Filament\Resources\Checksheets\ChecksheetResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateChecksheet extends CreateRecord
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
        ];
    }
}
