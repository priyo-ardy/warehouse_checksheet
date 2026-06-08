<?php

namespace App\Filament\Resources\Checksheets\Pages;

use App\Filament\Resources\Checksheets\ChecksheetResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditChecksheet extends EditRecord
{
    protected static string $resource = ChecksheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
