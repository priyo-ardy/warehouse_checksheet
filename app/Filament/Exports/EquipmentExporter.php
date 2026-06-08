<?php

namespace App\Filament\Exports;

use App\Models\Equipment;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

use function Symfony\Component\Clock\now;

class EquipmentExporter extends Exporter
{
    protected static ?string $model = Equipment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Equipment Name'),
            ExportColumn::make('brand')->label('Brand'),
            ExportColumn::make('serial_no')->label('Serial No.'),
            ExportColumn::make('leaders.name')->label('Lead Coordinator'),
            ExportColumn::make('is_active')->label('Equipment Status')->formatStateUsing(fn($state) => $state ? 'Active' : 'Not Active'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your equipment export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return 'equipment_list_' . now()->format('YmdHis');
    }
}
