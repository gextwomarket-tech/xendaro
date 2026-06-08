<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TradeResource\Pages;
use App\Models\Trade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class TradeResource extends Resource
{
    protected static ?string $model = Trade::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Trades';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations Trade')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('instrument_id')
                            ->relationship('instrument', 'symbol')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('account_type')
                            ->options(['demo' => 'Démo', 'real' => 'Réel'])
                            ->required(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Direction & Volume')
                    ->schema([
                        Forms\Components\Select::make('direction')
                            ->options(['BUY' => 'Achat', 'SELL' => 'Vente'])
                            ->required(),
                        Forms\Components\TextInput::make('volume')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('contract_size')
                            ->numeric(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Prix')
                    ->schema([
                        Forms\Components\TextInput::make('entry_price')
                            ->label('Prix d\'entrée')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('exit_price')
                            ->label('Prix de sortie')
                            ->numeric(),
                        Forms\Components\TextInput::make('stop_loss')
                            ->label('Stop Loss')
                            ->numeric(),
                        Forms\Components\TextInput::make('take_profit')
                            ->label('Take Profit')
                            ->numeric(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Risque & Marge')
                    ->schema([
                        Forms\Components\TextInput::make('margin')
                            ->numeric()
                            ->disabled(),
                    ])->columns(1),
                
                Forms\Components\Section::make('Résultats')
                    ->schema([
                        Forms\Components\TextInput::make('profit_loss')
                            ->label('P&L')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('profit_loss_pips')
                            ->label('P&L (Pips)')
                            ->numeric()
                            ->disabled(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Statut')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(['open' => 'Ouvert', 'closed' => 'Fermé'])
                            ->required(),
                        Forms\Components\TextInput::make('close_reason')
                            ->label('Raison de fermeture'),
                        Forms\Components\Toggle::make('is_bot')
                            ->label('Bot Trade'),
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
                Tables\Columns\TextColumn::make('direction')
                    ->badge()
                    ->colors([
                        'success' => 'BUY',
                        'danger' => 'SELL',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_type')
                    ->badge()
                    ->colors([
                        'info' => 'demo',
                        'success' => 'real',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entry_price')
                    ->label('Entrée')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exit_price')
                    ->label('Sortie')
                    ->numeric(),
                Tables\Columns\TextColumn::make('profit_loss')
                    ->label('P&L')
                    ->numeric()
                    ->color(fn (string $state) => $state >= 0 ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'open',
                        'warning' => 'closed',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(['open' => 'Ouvert', 'closed' => 'Fermé']),
                SelectFilter::make('direction')
                    ->options(['BUY' => 'Achat', 'SELL' => 'Vente']),
                SelectFilter::make('account_type')
                    ->options(['demo' => 'Démo', 'real' => 'Réel']),
                Tables\Filters\Filter::make('profit_range')
                    ->form([
                        Forms\Components\TextInput::make('profit_from')
                            ->label('P&L Min')
                            ->numeric(),
                        Forms\Components\TextInput::make('profit_to')
                            ->label('P&L Max')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['profit_from'] ?? null, fn ($q) => $q->where('profit_loss', '>=', $data['profit_from']))
                            ->when($data['profit_to'] ?? null, fn ($q) => $q->where('profit_loss', '<=', $data['profit_to']));
                    }),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('opened_from')
                            ->label('Ouvert le'),
                        Forms\Components\DatePicker::make('opened_to')
                            ->label('Ouvert jusqu\'au'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['opened_from'] ?? null, fn ($q) => $q->whereDate('opened_at', '>=', $data['opened_from']))
                            ->when($data['opened_to'] ?? null, fn ($q) => $q->whereDate('opened_at', '<=', $data['opened_to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrades::route('/'),
            'create' => Pages\CreateTrade::route('/create'),
            'edit' => Pages\EditTrade::route('/{record}/edit'),
        ];
    }
}

