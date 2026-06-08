<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletResource\Pages;
use App\Models\Wallet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationLabel = 'Portefeuilles';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du Portefeuille')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('currency')
                            ->maxLength(3)
                            ->default('USD')
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Soldes')
                    ->schema([
                        Forms\Components\TextInput::make('balance')
                            ->label('Solde Total')
                            ->numeric()
                            ->step(1)
                            ->minValue(0),
                        Forms\Components\TextInput::make('trading_balance')
                            ->label('Solde Trading')
                            ->numeric()
                            ->step(1)
                            ->minValue(0),
                        Forms\Components\TextInput::make('demo_balance')
                            ->label('Solde Démo')
                            ->numeric()
                            ->step(1)
                            ->minValue(0),
                    ])->columns(3),

                Forms\Components\Section::make('Historique')
                    ->schema([
                        Forms\Components\TextInput::make('total_deposited')
                            ->label('Total Déposé')
                            ->numeric()
                            ->step(1)
                            ->minValue(0),
                        Forms\Components\TextInput::make('total_withdrawn')
                            ->label('Total Retiré')
                            ->numeric()
                            ->step(1)
                            ->minValue(0),
                        Forms\Components\TextInput::make('margin_used')
                            ->label('Marge Utilisée')
                            ->numeric()
                            ->step(1)
                            ->minValue(0),
                    ])->columns(3),
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
                Tables\Columns\TextColumn::make('currency')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Solde Total')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 2, '.', ','))
                    ->sortable(),
                Tables\Columns\TextColumn::make('trading_balance')
                    ->label('Trading')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 2, '.', ','))
                    ->sortable(),
                Tables\Columns\TextColumn::make('demo_balance')
                    ->label('Démo')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 2, '.', ','))
                    ->sortable(),
                Tables\Columns\TextColumn::make('margin_used')
                    ->label('Marge')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 2, '.', ','))
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_deposited')
                    ->label('Dépôts')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 2, '.', ','))
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_withdrawn')
                    ->label('Retraits')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 2, '.', ','))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('currency')
                    ->options([
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                    ]),
                Tables\Filters\Filter::make('balance_range')
                    ->form([
                        Forms\Components\TextInput::make('balance_from')
                            ->label('Solde Min')
                            ->numeric(),
                        Forms\Components\TextInput::make('balance_to')
                            ->label('Solde Max')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['balance_from'] ?? null, fn ($q) => $q->where('balance', '>=', $data['balance_from']))
                            ->when($data['balance_to'] ?? null, fn ($q) => $q->where('balance', '<=', $data['balance_to']));
                    }),
                Tables\Filters\Filter::make('has_margin')
                    ->query(fn ($query) => $query->where('margin_used', '>', 0))
                    ->label('Avec marge utilisée'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWallets::route('/'),
            'create' => Pages\CreateWallet::route('/create'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
        ];
    }
}

