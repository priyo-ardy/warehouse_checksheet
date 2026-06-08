<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Exports\UserExporter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\QueryBuilder\Constraints\DateConstraint;
use Filament\QueryBuilder\Constraints\SelectConstraint;
use Filament\QueryBuilder\Constraints\TextConstraint;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->disk('public')
                    ->visibility('public')
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function (string $state) {
                        if (!$state) return '-';

                        // Pecah email jadi nama dan domain
                        $parts = explode('@', $state);
                        if (count($parts) !== 2) return $state;

                        $name = $parts[0];
                        $domain = $parts[1];

                        // Sensor nama, sisain 2 huruf pertama
                        // Contoh: "ardynur" jadi "ar*****"
                        $maskedName = \Illuminate\Support\Str::mask($name, '*', 2);

                        return $maskedName . '@' . $domain;
                    }),
                TextColumn::make('phone')
                    ->label('Phone Number')
                    ->prefix('+62')
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(
                        function (string $state) {
                            if (!$state) return '-';

                            return Str::mask($state, '*', 3, 6);
                        }
                    ),
                TextColumn::make('login_attempts')
                    ->label('Failed Login Attempts')
                    ->searchable()
                    ->toggleable()
                    ->alignCenter(),
                TextColumn::make('is_locked')
                    ->label('Locking Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Locked' : 'Unlocked')
                    ->color(fn($state) => $state ? 'danger' : 'success')
                    ->searchable()
                    ->toggleable()
                    ->alignCenter(),
                TextColumn::make('last_login')
                    ->label('Last Login')
                    ->date('d-M-Y H:i:s')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('last_login_from')
                    ->label('Last Login From')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('is_active')
                    ->label('User Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Enable' : 'Disable')
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->searchable()
                    ->toggleable()
                    ->alignCenter(),
                TextColumn::make('user_type')
                    ->label('User Type')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->formatStateUsing(fn($state) => ucwords(strtolower(str_replace('_', ' ', $state)))),
                TextColumn::make('remark')
                    ->label('Remark')
                    ->searchable()
                    ->toggleable()
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name')->label('Full Name'),
                        TextConstraint::make('email')->label('Email Address'),
                        TextConstraint::make('phone')->label('Phone Number'),
                        SelectConstraint::make('is_locked')
                            ->options([
                                '0' => 'No',
                                '1' => 'Yes',
                            ])->searchable(),
                        SelectConstraint::make('is_active')
                            ->label('User Status')
                            ->options([
                                '0' => 'Disabled',
                                '1' => 'Enabled'
                            ])
                            ->searchable()
                            ->native(false),
                        // Date
                        DateConstraint::make('last_login')
                            ->label('Last Login'),
                    ])
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->filtersFormWidth('4xl')
            ->persistFiltersInSession()
            ->filtersTriggerAction(
                fn($action) => $action
                    ->button()
                    ->label('Filter')
                    ->icon('heroicon-o-funnel')
            )
            ->recordActions([])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                Action::make('refresh')
                    ->label('Refresh')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->tooltip('Refresh')
                    ->action(fn() => null),
                ExportAction::make('export')
                    ->label('Export')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->tooltip('Export')
                    ->exporter(UserExporter::class)
            ])->defaultSort('name', 'asc');
    }
}
