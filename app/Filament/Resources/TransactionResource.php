<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        $isCompleted = $form->getRecord()?->status === 'completed';
        
        return $form
            ->schema([
                Forms\Components\Section::make('Informations Transaction')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Utilisateur (expéditeur)')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->required()
                            ->disabled($isCompleted),
                        Forms\Components\Select::make('type')
                            ->options([
                                'deposit'  => 'Dépôt',
                                'withdraw' => 'Retrait',
                                'transfer' => 'Transfert entre comptes',
                            ])
                            ->required()
                            ->live()
                            ->disabled($isCompleted),
                        Forms\Components\Select::make('recipient_virtual')
                            ->label('Compte destinataire (transfert)')
                            ->options(fn () => User::where('is_admin', false)
                                ->get()
                                ->mapWithKeys(fn ($u) => [$u->id => $u->email . ' — ' . ($u->first_name . ' ' . $u->last_name)])
                            )
                            ->searchable()
                            ->visible(fn (Get $get) => $get('type') === 'transfer')
                            ->required(fn (Get $get) => $get('type') === 'transfer')
                            ->disabled($isCompleted)
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($record) => $record?->details['recipient_id'] ?? null),
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant')
                            ->numeric()
                            ->step(1)
                            ->minValue(1)
                            ->required()
                            ->disabled($isCompleted),
                        Forms\Components\TextInput::make('currency')
                            ->maxLength(3)
                            ->default('USD')
                            ->required()
                            ->disabled($isCompleted),
                    ])->columns(2),
                
                Forms\Components\Section::make('Détails')
                    ->schema([
                        Forms\Components\Select::make('method')
                            ->options([
                                'bank_transfer' => 'Virement Bancaire',
                                'credit_card' => 'Carte Crédit',
                                'wallet' => 'Portefeuille',
                                'crypto' => 'Cryptomonnaie',
                            ])
                            ->disabled($isCompleted),
                        Forms\Components\TextInput::make('reference')
                            ->label('Référence')
                            ->maxLength(255)
                            ->disabled($isCompleted),
                    ])->columns(2),
                
                Forms\Components\Section::make('Statut & Traitement')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'En Attente',
                                'processing' => 'En Cours',
                                'completed' => 'Complétée',
                                'failed' => 'Échouée',
                                'dispute' => 'Litige',
                            ])
                            ->required()
                            ->disabled($isCompleted),
                        Forms\Components\DateTimePicker::make('processed_at')
                            ->label('Date Traitement')
                            ->disabled($isCompleted),
                        Forms\Components\TextInput::make('proof_url')
                            ->label('URL Preuve')
                            ->url()
                            ->disabled($isCompleted),
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Note Admin')
                            ->maxLength(500),
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
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'deposit'  => 'Dépôt',
                        'withdraw' => 'Retrait',
                        'transfer' => 'Transfert',
                        default    => $state,
                    })
                    ->colors([
                        'success' => 'deposit',
                        'danger'  => 'withdraw',
                        'info'    => 'transfer',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->label('Méthode')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->formatStateUsing(fn($state) => match($state) {
                        'pending' => 'En Attente',
                        'processing' => 'En Cours',
                        'completed' => 'Complétée',
                        'failed' => 'Échouée',
                        'dispute' => 'Litige',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'secondary' => 'dispute',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('processed_at')
                    ->label('Traitée')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(['deposit' => 'Dépôt', 'withdraw' => 'Retrait', 'transfer' => 'Transfert']),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'En Attente',
                        'processing' => 'En Cours',
                        'completed' => 'Complétée',
                        'failed' => 'Échouée',
                        'dispute' => 'Litige',
                    ]),
                SelectFilter::make('method')
                    ->options([
                        'bank_transfer' => 'Virement',
                        'credit_card' => 'Carte',
                        'wallet' => 'Portefeuille',
                        'crypto' => 'Crypto',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}

