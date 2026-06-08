<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrumentResource\Pages;
use App\Models\Instrument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class InstrumentResource extends Resource
{
    protected static ?string $model = Instrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Instruments';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informations Instrument')
                ->schema([
                    Forms\Components\TextInput::make('symbol')
                        ->required()
                        ->maxLength(20)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('category')
                        ->options([
                            'forex' => 'Forex',
                            'crypto' => 'Crypto',
                            'stocks' => 'Actions',
                            'commodities' => 'Matières Premières',
                            'indices' => 'Indices',
                        ])
                        ->required(),
                ])->columns(2),
            
            Forms\Components\Section::make('Prix & Spread')
                ->schema([
                    Forms\Components\TextInput::make('bid')
                        ->numeric()
                        ->step('0.00000001'),
                    Forms\Components\TextInput::make('ask')
                        ->numeric()
                        ->step('0.00000001'),
                    Forms\Components\TextInput::make('spread')
                        ->numeric()
                        ->step('0.00000001'),
                ])->columns(3),
            
            Forms\Components\Section::make('Changement 24h')
                ->schema([
                    Forms\Components\TextInput::make('change_24h')
                        ->numeric()
                        ->step('0.00000001'),
                    Forms\Components\TextInput::make('change_24h_percent')
                        ->numeric()
                        ->step('0.0001'),
                ])->columns(2),
            
            Forms\Components\Section::make('Statut')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('symbol')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),
            Tables\Columns\BadgeColumn::make('category')
                ->sortable(),
            Tables\Columns\TextColumn::make('bid')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('ask')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('spread')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('change_24h_percent')
                ->label('Changement %')
                ->color(fn (string $state) => $state >= 0 ? 'success' : 'danger')
                ->sortable(),
            Tables\Columns\IconColumn::make('is_active')
                ->boolean()
                ->sortable(),
        ])->filters([
            SelectFilter::make('category')
                ->options([
                    'forex' => 'Forex',
                    'crypto' => 'Crypto',
                    'stocks' => 'Actions',
                    'commodities' => 'Matières',
                    'indices' => 'Indices',
                ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstruments::route('/'),
            'create' => Pages\CreateInstrument::route('/create'),
            'edit' => Pages\EditInstrument::route('/{record}/edit'),
        ];
    }
}

