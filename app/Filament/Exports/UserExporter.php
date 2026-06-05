<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Full Name'),
            ExportColumn::make('email')->label('Email Address'),
            ExportColumn::make('phone')->label('Phone Number'),
            ExportColumn::make('login_attempts')->label('Number of Login Failed'),
            ExportColumn::make('is_locked')->label('Locking Status')->formatStateUsing(fn($state) => $state ? 'Locked' : 'Unlocked'),
            ExportColumn::make('last_login')->label('Last Login'),
            ExportColumn::make('last_login_from')->label('Last Login Address'),
            ExportColumn::make('is_active')->label('User Status')->formatStateUsing(fn($state) => $state ? 'Enable' : 'Disable'),
            ExportColumn::make('role')->label('User Role'),
            ExportColumn::make('remark')->label('Remark'),
            ExportColumn::make('user_agent')->label('Last Login Identity'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return 'user_list_' . now()->format('YmdHis');
    }
}
