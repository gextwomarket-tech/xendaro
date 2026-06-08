<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Utilisateurs';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations Personnelles')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('Prénom')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date de Naissance'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Adresse')
                    ->schema([
                        Forms\Components\TextInput::make('country')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('Authentification & Sécurité')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Actif',
                                'inactive' => 'Inactif',
                                'suspended' => 'Suspendu',
                                'banned' => 'Banni',
                            ])
                            ->default('active'),
                        Forms\Components\Toggle::make('email_verified_at')
                            ->label('Email Vérifié')
                            ->inline(false)
                            ->formatStateUsing(fn ($state) => !is_null($state))
                            ->dehydrateStateUsing(fn ($state) => $state ? now() : null),
                        Forms\Components\Toggle::make('two_factor_enabled')
                            ->label('2FA Activé')
                            ->inline(false)
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('KYC & Conformité')
                    ->schema([
                        Forms\Components\Select::make('kyc_status')
                            ->label('Statut KYC')
                            ->options([
                                'unverified' => 'Non vérifié',
                                'pending' => 'En Attente',
                                'verified' => 'Vérifié',
                                'rejected' => 'Rejeté',
                                'expired' => 'Expiré',
                            ]),
                        Forms\Components\TextInput::make('kyc_level')
                            ->label('Niveau KYC')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(3),
                    ])->columns(2),
                
                Forms\Components\Section::make('Parrainage & Préférences')
                    ->schema([
                        Forms\Components\TextInput::make('referral_code')
                            ->label('Code de Parrainage')
                            ->disabled(),
                        Forms\Components\TextInput::make('referred_by')
                            ->label('Parrainé par (ID)')
                            ->numeric(),
                        Forms\Components\TextInput::make('preferred_currency')
                            ->label('Devise Préférée')
                            ->maxLength(3)
                            ->default('USD'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Prénom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'suspended',
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email ✓')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('two_factor_enabled')
                    ->label('2FA')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kyc_status')
                    ->label('KYC')
                    ->badge()
                    ->colors([
                        'success' => 'verified',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                        'suspended' => 'Suspendu',
                        'banned' => 'Banni',
                    ]),
                SelectFilter::make('kyc_status')
                    ->label('Statut KYC')
                    ->options([
                        'pending' => 'En Attente',
                        'verified' => 'Vérifié',
                        'rejected' => 'Rejeté',
                    ]),
                TernaryFilter::make('email_verified_at')
                    ->label('Email Vérifié'),
                TernaryFilter::make('two_factor_enabled')
                    ->label('2FA Activé'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

