<?php

namespace App\Filament\Resources\MasterChecksheets;

use App\Filament\Resources\MasterChecksheets\Pages\CreateMasterChecksheet;
use App\Filament\Resources\MasterChecksheets\Pages\EditMasterChecksheet;
use App\Filament\Resources\MasterChecksheets\Pages\ListMasterChecksheets;
use App\Filament\Resources\MasterChecksheets\Pages\ViewMasterChecksheet;
use App\Filament\Resources\MasterChecksheets\Schemas\MasterChecksheetForm;
use App\Filament\Resources\MasterChecksheets\Schemas\MasterChecksheetInfolist;
use App\Filament\Resources\MasterChecksheets\Tables\MasterChecksheetsTable;
use App\Models\MasterChecksheet;
use App\Models\MasterChecksheetHeader;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterChecksheetResource extends Resource
{
    protected static ?string $model = MasterChecksheetHeader::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MasterChecksheetHeader';

    public static function form(Schema $schema): Schema
    {
        return MasterChecksheetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MasterChecksheetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MasterChecksheetsTable::configure($table);
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
            'index' => ListMasterChecksheets::route('/'),
            'create' => CreateMasterChecksheet::route('/create'),
            'view' => ViewMasterChecksheet::route('/{record}'),
            'edit' => EditMasterChecksheet::route('/{record}/edit'),
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
