<?php

namespace App\Filament\Resources\Checksheets;

use App\Filament\Resources\Checksheets\Pages\CreateChecksheet;
use App\Filament\Resources\Checksheets\Pages\EditChecksheet;
use App\Filament\Resources\Checksheets\Pages\ListChecksheets;
use App\Filament\Resources\Checksheets\Pages\ViewChecksheet;
use App\Filament\Resources\Checksheets\Schemas\ChecksheetForm;
use App\Filament\Resources\Checksheets\Schemas\ChecksheetInfolist;
use App\Filament\Resources\Checksheets\Tables\ChecksheetsTable;
use App\Models\ChecksheetHeader;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ChecksheetResource extends Resource
{
    protected static ?string $model = ChecksheetHeader::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Transaction';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Checksheet';
    protected static ?string $recordTitleAttribute = 'Checksheet';

    public static function form(Schema $schema): Schema
    {
        return ChecksheetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChecksheetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChecksheetsTable::configure($table);
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
            'index' => ListChecksheets::route('/'),
            'create' => CreateChecksheet::route('/create'),
            'view' => ViewChecksheet::route('/{record}'),
            'edit' => EditChecksheet::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
