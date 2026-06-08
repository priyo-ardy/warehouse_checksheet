<?php

namespace App\Filament\Resources\MasterChecksheets\Pages;

use App\Filament\Resources\MasterChecksheets\MasterChecksheetResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditMasterChecksheet extends EditRecord
{
    protected static string $resource = MasterChecksheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Cancel')->tooltip('Cancel')->icon(Heroicon::OutlinedArrowUturnLeft),
            // DeleteAction::make(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Arahkan langsung ke halaman 'view' dengan membawa ID record yang baru di-update
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
