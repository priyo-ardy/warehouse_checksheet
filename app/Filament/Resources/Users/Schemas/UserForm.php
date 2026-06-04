<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('User Avatar')
                    ->schema([
                        FileUpload::make('avatar')
                            ->image()
                            ->imageEditor()
                            ->avatar()
                            ->alignCenter()
                            ->visibility('public')
                            ->directory('user-avatar')
                            ->disk('public')
                            ->columnSpanFull()
                            ->imagePreviewHeight('350px')
                            ->removeUploadedFileButtonPosition('right')
                            ->saveRelationshipsUsing(null)
                            ->hiddenLabel()
                    ])
                    ->columnSpan(3),
                Section::make()
                    ->description('User Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->maxLength(150)
                            ->required()
                            ->placeholder('Full Name')
                            ->autocomplete(false)
                            ->columnSpan(4)
                            ->trim(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(150)
                            ->placeholder('Email Address')
                            ->autocomplete(false)
                            ->validationMessages([
                                'Email address already registered'
                            ])
                            ->trim()
                            ->columnSpan(4),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->required()
                            ->maxLength(25)
                            ->unique(ignoreRecord: true)
                            ->trim()
                            ->autocomplete(false)
                            ->validationMessages([
                                'Phone number already registered'
                            ])
                            ->columnSpan(4)
                            ->placeholder('Phone Number')
                            ->trim()
                            ->prefix('+62'),
                        TextInput::make('password')
                            ->password()
                            ->trim()
                            ->label('Password')
                            ->required(fn($context) => $context === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->mutateDehydratedStateUsing(fn($state) => Hash::make($state))
                            ->revealable()
                            ->columnSpan(4),
                        Textarea::make('remark')
                            ->label('Remark')
                            ->nullable()
                            ->trim()
                            ->disableGrammarly()
                            ->columnSpan(8)
                            ->placeholder('Write additional information here ...')
                    ])
                    ->columns(12)
                    ->columnSpan(9)
            ])
            ->columns(12);
    }
}
