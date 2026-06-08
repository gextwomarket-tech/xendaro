<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Commandes';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations Commande')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('instrument_id')
                            ->relationship('instrument', 'symbol')
                            ->searchable()
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Détails Commande')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options(['limit' => 'Limite', 'market' => 'Marché', 'stop' => 'Stop'])
                            ->required(),
                        Forms\Components\Select::make('direction')
                            ->options(['BUY' => 'Achat', 'SELL' => 'Vente'])
                            ->required(),
                        Forms\Components\TextInput::make('volume')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Niveaux')
                    ->schema([
                        Forms\Components\TextInput::make('stop_loss')
                            ->label('Stop Loss')
                            ->numeric(),
                        Forms\Components\TextInput::make('take_profit')
                            ->label('Take Profit')
                            ->numeric(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Statut')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'En Attente',
                                'executed' => 'Exécutée',
                                'cancelled' => 'Annulée',
                            ])
                            ->required(),
                        Forms\Components\DateTimePicker::make('executed_at')
                            ->label('Date Exécution'),
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
                Tables\Columns\TextColumn::make('instrument.symbol')
                    ->label('Instrument')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'info' => 'limit',
                        'success' => 'market',
                        'warning' => 'stop',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('direction')
                    ->badge()
                    ->colors([
                        'success' => 'BUY',
                        'danger' => 'SELL',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'executed',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'En Attente',
                        'executed' => 'Exécutée',
                        'cancelled' => 'Annulée',
                    ]),
                SelectFilter::make('type')
                    ->options(['limit' => 'Limite', 'market' => 'Marché', 'stop' => 'Stop']),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

