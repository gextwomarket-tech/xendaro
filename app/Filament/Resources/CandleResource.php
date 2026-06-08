<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandleResource\Pages;
use App\Models\Candle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class CandleResource extends Resource
{
    protected static ?string $model = Candle::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Bougies (OHLC)';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Instrument')
                ->schema([
                    Forms\Components\Select::make('instrument_id')
                        ->relationship('instrument', 'symbol')
                        ->searchable()
                        ->required(),
                ])->columns(1),
            
            Forms\Components\Section::make('Prix OHLC')
                ->schema([
                    Forms\Components\TextInput::make('open')
                        ->numeric()
                        ->step('0.00000001')
                        ->required(),
                    Forms\Components\TextInput::make('high')
                        ->numeric()
                        ->step('0.00000001')
                        ->required(),
                    Forms\Components\TextInput::make('low')
                        ->numeric()
                        ->step('0.00000001')
                        ->required(),
                    Forms\Components\TextInput::make('close')
                        ->numeric()
                        ->step('0.00000001')
                        ->required(),
                ])->columns(2),
            
            Forms\Components\Section::make('Volume & Temps')
                ->schema([
                    Forms\Components\TextInput::make('volume')
                        ->numeric()
                        ->step('0.00000001')
                        ->required(),
                    Forms\Components\DateTimePicker::make('timestamp')
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('instrument.symbol')
                ->label('Instrument')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('open')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('high')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('low')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('close')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('volume')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('timestamp')
                ->dateTime()
                ->sortable(),
        ])->filters([
            SelectFilter::make('instrument')
                ->relationship('instrument', 'symbol'),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->defaultSort('timestamp', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandles::route('/'),
            'create' => Pages\CreateCandle::route('/create'),
            'edit' => Pages\EditCandle::route('/{record}/edit'),
        ];
    }
}
