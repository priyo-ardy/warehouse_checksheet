<?php

namespace App\Filament\Resources\Equipments;

use App\Filament\Resources\Equipments\Pages\CreateEquipments;
use App\Filament\Resources\Equipments\Pages\EditEquipments;
use App\Filament\Resources\Equipments\Pages\ListEquipments;
use App\Filament\Resources\Equipments\Pages\ViewEquipments;
use App\Filament\Resources\Equipments\Schemas\EquipmentsForm;
use App\Filament\Resources\Equipments\Schemas\EquipmentsInfolist;
use App\Filament\Resources\Equipments\Tables\EquipmentsTable;
use App\Models\Equipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class EquipmentsResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'Equipment';

    public static function form(Schema $schema): Schema
    {
        return EquipmentsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EquipmentsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EquipmentsTable::configure($table);
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
            'index' => ListEquipments::route('/'),
            'create' => CreateEquipments::route('/create'),
            'view' => ViewEquipments::route('/{record}'),
            'edit' => EditEquipments::route('/{record}/edit'),
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
