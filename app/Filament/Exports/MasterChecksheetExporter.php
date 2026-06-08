<?php

namespace App\Filament\Exports;

use App\Models\MasterChecksheet;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class MasterChecksheetExporter extends Exporter
{
    protected static ?string $model = MasterChecksheet::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your master checksheet export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return 'master_checksheet_list_' . now()->format('YmdHis');
    }
}
