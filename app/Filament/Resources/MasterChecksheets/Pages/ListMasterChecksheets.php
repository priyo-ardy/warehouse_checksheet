<?php

namespace App\Filament\Resources\MasterChecksheets\Pages;

use App\Filament\Resources\MasterChecksheets\MasterChecksheetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMasterChecksheets extends ListRecords
{
    protected static string $resource = MasterChecksheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
