<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoxBotResource\Pages;
use App\Models\FoxBot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FoxBotResource extends Resource
{
    protected static ?string $model = FoxBot::class;
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'FoxBot';
    protected static ?string $navigationGroup = 'Trading';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identité du Bot')
                ->schema([
                    Forms\Components\TextInput::make('name_bot')
                        ->label('Nom du bot')
                        ->required()
                        ->maxLength(100)
                        ->placeholder('Ex: FoxBot Alpha'),
                    Forms\Components\TextInput::make('avatar_emoji')
                        ->label('Emoji / Icône')
                        ->default('🤖')
                        ->maxLength(10),
                    Forms\Components\TextInput::make('strategy_label')
                        ->label('Stratégie')
                        ->maxLength(100)
                        ->placeholder('Ex: Scalping M1, Swing H4'),
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(2)
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Bot actif')
                        ->default(true)
                        ->columnSpanFull(),
                ])->columns(3),

            Forms\Components\Section::make('Performance Horaire')
                ->description('Taux de gain/perte appliqués par heure sur le solde utilisateur.')
                ->schema([
                    Forms\Components\TextInput::make('percentage_win_hour')
                        ->label('% Gain / heure')
                        ->numeric()
                        ->step('0.0001')
                        ->default(2.50)
                        ->suffix('%/h')
                        ->required()
                        ->helperText('Ex: 2.5 = +2.5% par heure'),
                    Forms\Components\TextInput::make('percentage_lost_hour')
                        ->label('% Perte / heure (drawdown)')
                        ->numeric()
                        ->step('0.0001')
                        ->default(0.50)
                        ->suffix('%/h')
                        ->required()
                        ->helperText('Ex: 0.5 = -0.5% par heure en période de perte'),
                    Forms\Components\TextInput::make('win_rate')
                        ->label('Taux de réussite cible')
                        ->numeric()
                        ->step('0.01')
                        ->default(75.00)
                        ->suffix('%')
                        ->required()
                        ->minValue(0)
                        ->maxValue(100),
                ])->columns(3),

            Forms\Components\Section::make('Paramètres de Trade')
                ->schema([
                    Forms\Components\TextInput::make('risk_per_trade')
                        ->label('Risque par trade')
                        ->numeric()
                        ->step('0.01')
                        ->default(2.00)
                        ->suffix('%')
                        ->helperText('% du solde risqué par position'),
                    Forms\Components\TextInput::make('tp_multiplier')
                        ->label('Multiplicateur TP')
                        ->numeric()
                        ->step('0.01')
                        ->default(2.00)
                        ->helperText('Take Profit = SL × multiplicateur'),
                    Forms\Components\TextInput::make('max_concurrent_positions')
                        ->label('Positions max simultanées')
                        ->numeric()
                        ->step(1)
                        ->default(3)
                        ->minValue(1)
                        ->maxValue(20),
                    Forms\Components\TextInput::make('min_hold_seconds')
                        ->label('Durée min (secondes)')
                        ->numeric()
                        ->default(5)
                        ->suffix('s'),
                    Forms\Components\TextInput::make('max_hold_seconds')
                        ->label('Durée max (secondes)')
                        ->numeric()
                        ->default(25)
                        ->suffix('s'),
                ])->columns(3),

            Forms\Components\Section::make('Notes Admin')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notes internes')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('avatar_emoji')
                ->label('')
                ->width(40),
            Tables\Columns\TextColumn::make('name_bot')
                ->label('Nom')
                ->searchable()
                ->sortable()
                ->weight('bold'),
            Tables\Columns\TextColumn::make('strategy_label')
                ->label('Stratégie')
                ->placeholder('—')
                ->badge()
                ->color('info'),
            Tables\Columns\TextColumn::make('percentage_win_hour')
                ->label('Gain/h')
                ->formatStateUsing(fn ($state) => '+' . number_format($state, 2) . '%')
                ->color('success')
                ->sortable(),
            Tables\Columns\TextColumn::make('win_rate')
                ->label('Win Rate')
                ->formatStateUsing(fn ($state) => number_format($state, 1) . '%')
                ->badge()
                ->color(fn ($state) => $state >= 70 ? 'success' : ($state >= 50 ? 'warning' : 'danger'))
                ->sortable(),
            Tables\Columns\TextColumn::make('max_concurrent_positions')
                ->label('Pos. max')
                ->alignCenter(),
            Tables\Columns\TextColumn::make('total_trades')
                ->label('Trades total')
                ->sortable()
                ->alignCenter(),
            Tables\Columns\TextColumn::make('total_pnl')
                ->label('P&L Total')
                ->formatStateUsing(fn ($state) => ($state >= 0 ? '+' : '') . number_format($state, 2) . ' $')
                ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                ->sortable(),
            Tables\Columns\IconColumn::make('is_active')
                ->label('Actif')
                ->boolean()
                ->trueColor('success')
                ->falseColor('danger')
                ->sortable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Modifié')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\TernaryFilter::make('is_active')
                ->label('Statut')
                ->trueLabel('Bots actifs')
                ->falseLabel('Bots inactifs'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('toggle')
                ->label(fn (FoxBot $record) => $record->is_active ? 'Désactiver' : 'Activer')
                ->icon(fn (FoxBot $record) => $record->is_active ? 'heroicon-o-pause' : 'heroicon-o-play')
                ->color(fn (FoxBot $record) => $record->is_active ? 'warning' : 'success')
                ->action(fn (FoxBot $record) => $record->update(['is_active' => !$record->is_active]))
                ->requiresConfirmation(),
            Tables\Actions\DeleteAction::make(),
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
            'index'  => Pages\ListFoxBots::route('/'),
            'create' => Pages\CreateFoxBot::route('/create'),
            'edit'   => Pages\EditFoxBot::route('/{record}/edit'),
        ];
    }
}
