<?php

namespace App\Filament\Resources\Approvals;

use App\Filament\Resources\Approvals\Pages\CreateApproval;
use App\Filament\Resources\Approvals\Pages\EditApproval;
use App\Filament\Resources\Approvals\Pages\ListApprovals;
use App\Filament\Resources\Approvals\Pages\ViewApproval;
use App\Filament\Resources\Approvals\Schemas\ApprovalForm;
use App\Filament\Resources\Approvals\Schemas\ApprovalInfolist;
use App\Filament\Resources\Approvals\Tables\ApprovalsTable;
use App\Models\Approval;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class ApprovalResource extends Resource
{
    protected static ?string $model = Approval::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Transaction';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'Approval';

    public static function form(Schema $schema): Schema
    {
        return ApprovalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApprovalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApprovalsTable::configure($table);
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
            'index' => ListApprovals::route('/'),
            'create' => CreateApproval::route('/create'),
            'view' => ViewApproval::route('/{record}'),
            'edit' => EditApproval::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $tableName = (new static::$model)->getTable();

        return parent::getEloquentQuery()
            ->where('approver', Auth::id())
            ->whereNull('status')
            ->where(function (Builder $query) use ($tableName) {
                $query->where('order', 1)
                    ->orWhereExists(function ($subQuery) use ($tableName) {
                        $subQuery->select(DB::raw(1))
                            ->from($tableName . ' as prev')
                            ->whereColumn('prev.document_id', $tableName . '.document_id')
                            ->whereRaw("prev.order = {$tableName}.order - 1")
                            ->where('prev.status', 1);
                    });
            });
    }
}
