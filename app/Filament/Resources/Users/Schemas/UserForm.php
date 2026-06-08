<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

use function Symfony\Component\Clock\now;

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
                            ->hiddenLabel()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, Get $get) {
                                $namaUser = $get('name') ?? 'avatar';

                                $slugName = Str::slug($namaUser);

                                return (string) str($slugName . '-' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension());
                            })
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
                            ->trim()
                            ->live(onBlur: true),
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
                        Select::make('user_type')
                            ->label('User Type')
                            ->options([
                                'administrator' => 'Administrator',
                                'user' => 'User',
                                'leader' => 'Leader',
                                'spv' => 'Supervisor',
                                'she' => 'SHE'
                            ])
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->columnSpan(3),
                        Textarea::make('remark')
                            ->label('Remark')
                            ->nullable()
                            ->trim()
                            ->disableGrammarly()
                            ->columnSpanFull()
                            ->placeholder('Write additional information here ...')
                    ])
                    ->columns(12)
                    ->columnSpan(9)
            ])
            ->columns(12);
    }
}
