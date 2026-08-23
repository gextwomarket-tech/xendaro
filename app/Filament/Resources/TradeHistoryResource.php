<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TradeHistoryResource\Pages;
use App\Filament\Resources\TradeHistoryResource\RelationManagers;
use App\Models\TradeHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TradeHistoryResource extends Resource
{
    protected static ?string $model = TradeHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationGroup = 'Trading';

    protected static ?string $modelLabel = 'Trade';

    protected static ?string $pluralModelLabel = 'Historique des trades';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('market_instrument_id')
                    ->relationship('instrument', 'nom')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('mode')
                    ->options(['demo' => 'Demo', 'reel' => 'Reel'])
                    ->required(),
                Forms\Components\Select::make('sens')
                    ->options(['buy' => 'Buy', 'sell' => 'Sell'])
                    ->required(),
                Forms\Components\Select::make('type_ordre')
                    ->options([
                        'marche' => 'Marche',
                        'buy_limit' => 'Buy Limit',
                        'sell_limit' => 'Sell Limit',
                        'buy_stop' => 'Buy Stop',
                        'sell_stop' => 'Sell Stop',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('volume')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('prix_ouverture')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('prix_cloture')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('stop_loss')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('take_profit')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('marge_utilisee')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('profit_perte')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('statut')
                    ->options(['ouvert' => 'Ouvert', 'cloture' => 'Cloture'])
                    ->required(),
                Forms\Components\DateTimePicker::make('ouvert_le')
                    ->required(),
                Forms\Components\DateTimePicker::make('cloture_le'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('instrument.nom')
                    ->label('Instrument')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mode')
                    ->badge(),
                Tables\Columns\TextColumn::make('sens')
                    ->badge()
                    ->color(fn (string $state) => $state === 'buy' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('volume')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_ouverture')
                    ->numeric(5)
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_cloture')
                    ->numeric(5)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('marge_utilisee')
                    ->label('Marge')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('profit_perte')
                    ->label('P&L')
                    ->money('USD')
                    ->sortable()
                    ->color(fn (?string $state) => $state === null ? null : ((float) $state >= 0 ? 'success' : 'danger')),
                Tables\Columns\TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state) => $state === 'ouvert' ? 'info' : 'gray'),
                Tables\Columns\TextColumn::make('ouvert_le')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cloture_le')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('ouvert_le', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('mode')->options(['demo' => 'Demo', 'reel' => 'Reel']),
                Tables\Filters\SelectFilter::make('statut')->options(['ouvert' => 'Ouvert', 'cloture' => 'Cloture']),
                Tables\Filters\SelectFilter::make('sens')->options(['buy' => 'Buy', 'sell' => 'Sell']),
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
            'index' => Pages\ListTradeHistories::route('/'),
            'create' => Pages\CreateTradeHistory::route('/create'),
            'edit' => Pages\EditTradeHistory::route('/{record}/edit'),
        ];
    }
}
