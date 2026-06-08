<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationPreferenceResource\Pages;
use App\Models\NotificationPreference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter;

class NotificationPreferenceResource extends Resource
{
    protected static ?string $model = NotificationPreference::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Préf. Notifications';
    protected static ?int $navigationSort = 16;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Utilisateur')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'email')
                        ->searchable()
                        ->required(),
                ])->columns(1),
            
            Forms\Components\Section::make('Préférences')
                ->schema([
                    Forms\Components\Toggle::make('email_on_trade')
                        ->label('Email à l\'ouverture du trade')
                        ->inline(false),
                    Forms\Components\Toggle::make('email_on_close')
                        ->label('Email à la fermeture du trade')
                        ->inline(false),
                    Forms\Components\Toggle::make('sms_alerts')
                        ->label('Alertes SMS')
                        ->inline(false),
                    Forms\Components\Toggle::make('newsletter')
                        ->label('Newsletter Hebdo')
                        ->inline(false),
                ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.email')
                ->label('Utilisateur')
                ->searchable(),
            Tables\Columns\IconColumn::make('email_on_trade')
                ->boolean(),
            Tables\Columns\IconColumn::make('email_on_close')
                ->boolean(),
            Tables\Columns\IconColumn::make('sms_alerts')
                ->boolean(),
            Tables\Columns\IconColumn::make('newsletter')
                ->boolean(),
        ])->filters([
            TernaryFilter::make('email_on_trade'),
            TernaryFilter::make('sms_alerts'),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationPreferences::route('/'),
            'edit' => Pages\EditNotificationPreference::route('/{record}/edit'),
        ];
    }
}
