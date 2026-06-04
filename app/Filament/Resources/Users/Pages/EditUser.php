<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Cancel')->icon(Heroicon::OutlinedArrowUturnLeft),
            DeleteAction::make()->label('Delete')->icon(Heroicon::Trash),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Arahkan langsung ke halaman 'view' dengan membawa ID record yang baru di-update
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
