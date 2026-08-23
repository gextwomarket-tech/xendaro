<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletTransactionResource\Pages;
use App\Models\WalletTransaction;
use App\Services\WalletTransactionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Espace Client';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('payment_method_id')
                    ->relationship('paymentMethod', 'nom')
                    ->default(null),
                Forms\Components\Select::make('type')
                    ->options(['depot' => 'Dépôt', 'retrait' => 'Retrait'])
                    ->required(),
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'refuse' => 'Refusé',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('reference')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('note_admin')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'depot' ? 'Dépôt' : 'Retrait')
                    ->color(fn (string $state) => $state === 'depot' ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('montant')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.nom')
                    ->label('Méthode'),
                Tables\Columns\TextColumn::make('statut')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'refuse' => 'Refusé',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'valide' => 'success',
                        'refuse' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'refuse' => 'Refusé',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options(['depot' => 'Dépôt', 'retrait' => 'Retrait']),
            ])
            ->actions([
                Tables\Actions\Action::make('valider')
                    ->label('Valider')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (WalletTransaction $record) => $record->statut === 'en_attente')
                    ->requiresConfirmation()
                    ->action(function (WalletTransaction $record) {
                        WalletTransactionService::approve($record);
                        Notification::make()->title('Transaction validée')->success()->send();
                    }),
                Tables\Actions\Action::make('refuser')
                    ->label('Refuser')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (WalletTransaction $record) => $record->statut === 'en_attente')
                    ->requiresConfirmation()
                    ->action(function (WalletTransaction $record) {
                        WalletTransactionService::reject($record);
                        Notification::make()->title('Transaction refusée')->danger()->send();
                    }),
                Tables\Actions\EditAction::make(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWalletTransactions::route('/'),
            'create' => Pages\CreateWalletTransaction::route('/create'),
            'edit' => Pages\EditWalletTransaction::route('/{record}/edit'),
        ];
    }
}
