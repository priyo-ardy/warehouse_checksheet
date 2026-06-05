<?php

namespace App\Filament\Resources\Equipments\Pages;

use App\Filament\Resources\Equipments\EquipmentsResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditEquipments extends EditRecord
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
            // ViewAction::make()->label('Cancel')->tooltip('Cancel')->icon(Heroicon::OutlinedArrowUturnLeft),
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
