<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Parametres';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'crypto' => 'Crypto',
                        'e-wallet' => 'E-wallet',
                        'virement' => 'Virement bancaire',
                        'carte' => 'Carte bancaire',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('instructions')
                    ->helperText('Texte explicatif affiche au client (visible avant les details de depot).')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('details_paiement')
                    ->label('Détails du dépôt')
                    ->helperText('Adresse crypto, email PayPal, identifiant Perfect Money, IBAN... affiche au client avec un bouton copier (+ QR code si type = crypto).')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('frais')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('delai_traitement')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('est_actif')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('frais')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delai_traitement')
                    ->searchable(),
                Tables\Columns\IconColumn::make('est_actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
