<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalRequestResource\Pages;
use App\Models\WithdrawalRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class WithdrawalRequestResource extends Resource
{
    protected static ?string $model = WithdrawalRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Retraits';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        $isCompleted = in_array($form->getRecord()?->status, ['completed', 'rejected', 'cancelled']);
        
        return $form
            ->schema([
                Forms\Components\Section::make('Informations utilisateur')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Utilisateur')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->required()
                            ->disabled($isCompleted),
                        Forms\Components\TextInput::make('reference')
                            ->label('Référence')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Montants')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant demandé')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('fees')
                            ->label('Frais (1%)')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('net_amount')
                            ->label('Montant net')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('currency')
                            ->label('Devise')
                            ->maxLength(3)
                            ->disabled(),
                    ])->columns(4),

                Forms\Components\Section::make('Méthode de paiement')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'bank_transfer' => 'Virement bancaire',
                                'cryptocurrency' => 'Cryptomonnaie',
                                'card' => 'Retrait par Carte bancaire',
                            ])
                            ->required()
                            ->disabled($isCompleted),

                        // Champs Virement bancaire
                        Forms\Components\Section::make('Détails bancaires')
                            ->schema([
                                Forms\Components\TextInput::make('bank_account_holder')
                                    ->label('Titulaire du compte'),
                                Forms\Components\TextInput::make('bank_account_number')
                                    ->label('IBAN / Numéro de compte'),
                                Forms\Components\TextInput::make('bank_swift')
                                    ->label('BIC / SWIFT'),
                                Forms\Components\TextInput::make('bank_name')
                                    ->label('Nom de la banque'),
                            ])->columns(2)
                            ->visible(fn (Get $get) => $get('payment_method') === 'bank_transfer'),

                        // Champs Cryptomonnaie
                        Forms\Components\Section::make('Détails cryptomonnaie')
                            ->schema([
                                Forms\Components\TextInput::make('crypto_type')
                                    ->label('Type de crypto'),
                                Forms\Components\Textarea::make('crypto_address')
                                    ->label('Adresse du portefeuille')
                                    ->rows(3),
                            ])
                            ->visible(fn (Get $get) => $get('payment_method') === 'cryptocurrency'),

                        // Champs Carte bancaire
                        Forms\Components\Section::make('Détails de la carte')
                            ->schema([
                                Forms\Components\TextInput::make('card_holder_name')
                                    ->label('Titulaire de la carte'),
                                Forms\Components\TextInput::make('card_number')
                                    ->label('Numéro de carte (4 derniers chiffres)'),
                                Forms\Components\TextInput::make('card_expiry')
                                    ->label('Expiration (MM/YY)'),
                                Forms\Components\TextInput::make('card_bank_name')
                                    ->label('Banque émettrice'),
                            ])->columns(2)
                            ->visible(fn (Get $get) => $get('payment_method') === 'card'),
                    ]),

                Forms\Components\Section::make('Statut et notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'En attente',
                                'approved' => 'Approuvée',
                                'rejected' => 'Rejetée',
                                'completed' => 'Complétée',
                                'cancelled' => 'Annulée',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Raison du rejet')
                            ->rows(3)
                            ->visible(fn (Get $get) => $get('status') === 'rejected'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes administrateur')
                            ->rows(3),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->label('Référence')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->currency),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Méthode')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'bank_transfer' => 'Virement',
                        'cryptocurrency' => 'Crypto',
                        'card' => 'Carte',
                        default => $state
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'En attente',
                        'approved' => 'Approuvée',
                        'rejected' => 'Rejetée',
                        'completed' => 'Complétée',
                        'cancelled' => 'Annulée',
                        default => $state
                    })
                    ->color(fn ($state) => match($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'rejected' => 'danger',
                        'completed' => 'success',
                        'cancelled' => 'gray',
                        default => 'secondary'
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'approved' => 'Approuvée',
                        'rejected' => 'Rejetée',
                        'completed' => 'Complétée',
                        'cancelled' => 'Annulée',
                    ]),
                SelectFilter::make('payment_method')
                    ->options([
                        'bank_transfer' => 'Virement bancaire',
                        'cryptocurrency' => 'Cryptomonnaie',
                        'card' => 'Retrait par Carte',
                    ]),
                TernaryFilter::make('created_at')
                    ->label('Date de création')
                    ->queries(
                        true: fn ($query) => $query->whereDate('created_at', now()),
                        false: fn ($query) => $query->whereDate('created_at', '!=', now()),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawalRequests::route('/'),
            'create' => Pages\CreateWithdrawalRequest::route('/create'),
            'edit' => Pages\EditWithdrawalRequest::route('/{record}/edit'),
        ];
    }
}
