<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatformSettingResource\Pages;
use App\Models\PlatformSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlatformSettingResource extends Resource
{
    protected static ?string $model = PlatformSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Paramètres Plateforme';
    protected static ?string $modelLabel = 'Paramètre Plateforme';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Paramètres')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Identité')
                            ->schema([
                                Forms\Components\TextInput::make('platform_name')
                                    ->label('Nom Plateforme')
                                    ->required(),
                                Forms\Components\TextInput::make('platform_slogan')
                                    ->label('Slogan'),
                                Forms\Components\TextInput::make('platform_logo')
                                    ->label('URL Logo'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Contact')
                            ->schema([
                                Forms\Components\TextInput::make('contact_email')
                                    ->label('Email Contact')
                                    ->email(),
                                Forms\Components\TextInput::make('contact_phone')
                                    ->label('Téléphone'),
                                Forms\Components\TextInput::make('contact_whatsapp')
                                    ->label('WhatsApp'),
                                Forms\Components\TextInput::make('contact_telegram')
                                    ->label('Telegram'),
                                Forms\Components\TextInput::make('support_email')
                                    ->label('Email Support')
                                    ->email(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Localisation')
                            ->schema([
                                Forms\Components\TextInput::make('address_line_1')
                                    ->label('Adresse 1'),
                                Forms\Components\TextInput::make('address_line_2')
                                    ->label('Adresse 2'),
                                Forms\Components\TextInput::make('city')
                                    ->label('Ville'),
                                Forms\Components\TextInput::make('postal_code')
                                    ->label('Code Postal'),
                                Forms\Components\TextInput::make('country')
                                    ->label('Pays'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Trading')
                            ->schema([
                                Forms\Components\TextInput::make('min_deposit')
                                    ->label('Dépôt Min')
                                    ->numeric(),
                                Forms\Components\TextInput::make('max_deposit')
                                    ->label('Dépôt Max')
                                    ->numeric(),
                                Forms\Components\TextInput::make('min_withdrawal')
                                    ->label('Retrait Min')
                                    ->numeric(),
                                Forms\Components\TextInput::make('max_withdrawal')
                                    ->label('Retrait Max')
                                    ->numeric(),
                                Forms\Components\Select::make('kyc_level_required')
                                    ->label('Niveau KYC Requis')
                                    ->options([1 => 'Niveau 1', 2 => 'Niveau 2', 3 => 'Niveau 3']),
                                Forms\Components\Toggle::make('maintenance_mode')
                                    ->label('Mode Maintenance'),
                                Forms\Components\TextInput::make('bot_profit_rate_per_hour')
                                    ->label('Taux Bot (% / heure)')
                                    ->numeric()
                                    ->step(0.1)
                                    ->minValue(0.1)
                                    ->maxValue(50)
                                    ->suffix('%/h')
                                    ->helperText('Taux de gain simulé par le bot par heure (ex: 2.5 = +2.5% de la balance/h)'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Réseaux Sociaux')
                            ->schema([
                                Forms\Components\TextInput::make('social_facebook')
                                    ->label('Facebook'),
                                Forms\Components\TextInput::make('social_twitter')
                                    ->label('Twitter'),
                                Forms\Components\TextInput::make('social_linkedin')
                                    ->label('LinkedIn'),
                                Forms\Components\TextInput::make('social_instagram')
                                    ->label('Instagram'),
                                Forms\Components\TextInput::make('social_youtube')
                                    ->label('YouTube'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform_name')
                    ->label('Plateforme'),
                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('country')
                    ->label('Pays'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Dernière Modif')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformSettings::route('/'),
            'edit' => Pages\EditPlatformSetting::route('/{record}/edit'),
        ];
    }
}
