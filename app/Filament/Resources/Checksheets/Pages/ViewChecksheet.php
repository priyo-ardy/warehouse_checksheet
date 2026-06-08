<?php

namespace App\Filament\Resources\Checksheets\Pages;

use App\Filament\Resources\Checksheets\ChecksheetResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChecksheet extends ViewRecord
{
    protected static string $resource = ChecksheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
