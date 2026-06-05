<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\CreateActivityLogs;
use App\Filament\Resources\ActivityLogs\Pages\EditActivityLogs;
use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLogs\Pages\ViewActivityLogs;
use App\Filament\Resources\ActivityLogs\Schemas\ActivityLogsForm;
use App\Filament\Resources\ActivityLogs\Schemas\ActivityLogsInfolist;
use App\Filament\Resources\ActivityLogs\Tables\ActivityLogsTable;
use App\Models\ActivityLogs;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ActivityLogsResource extends Resource
{
    protected static ?string $model = ActivityLogs::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ActivityLogs';

    public static function form(Schema $schema): Schema
    {
        return ActivityLogsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityLogsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'create' => CreateActivityLogs::route('/create'),
            'view' => ViewActivityLogs::route('/{record}'),
            'edit' => EditActivityLogs::route('/{record}/edit'),
        ];
    }
}
