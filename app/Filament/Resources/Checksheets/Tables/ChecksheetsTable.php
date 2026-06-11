<?php

namespace App\Filament\Resources\Checksheets\Tables;

use App\Models\Approval;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ChecksheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Checksheet Code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('doc_status')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->default('Waiting for approval')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => $state,
                    }),
                TextColumn::make('equipment.name')
                    ->label('Equipment Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('leader.name')
                    ->label('Leader')
                    ->searchable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('currentApprover.approverUser.name')
                    ->label('Current Approval')
                    ->searchable(function ($query, string $search) {
                        $query->whereHas('currentApproval.approverUser', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        })->orWhereHas('creator', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->state(function ($record) {
                        if ($record->doc_status === 'approved') {
                            return $record->creator?->name . ' (Owner)';
                        }

                        return $record->currentApprover?->approverUser?->name;
                        // return $record->currentApproval?->approverUser?->name;
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('remark')
                    ->label('Remark')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('creator.name')
                    ->label('Creator')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created Date')
                    ->date('d M Y H:i:s')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                Action::make('refresh')
                    ->label('Refresh')
                    ->tooltip('Refresh')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->action(fn() => null)
            ]);
    }
}
