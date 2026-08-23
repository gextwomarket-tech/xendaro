<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketInstrumentResource\Pages;
use App\Filament\Resources\MarketInstrumentResource\RelationManagers;
use App\Models\MarketInstrument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MarketInstrumentResource extends Resource
{
    protected static ?string $model = MarketInstrument::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('symbole_interne')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('categorie')
                    ->required(),
                Forms\Components\TextInput::make('symbole_provider_externe')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('provider')
                    ->required()
                    ->maxLength(255)
                    ->default('tradingview'),
                Forms\Components\TextInput::make('spread')
                    ->required()
                    ->numeric()
                    ->default(0.00000),
                Forms\Components\TextInput::make('levier_max')
                    ->required()
                    ->numeric()
                    ->default(100),
                Forms\Components\TextInput::make('prix_reference')
                    ->required()
                    ->numeric()
                    ->default(0.00000),
                Forms\Components\Toggle::make('est_actif')
                    ->required(),
                Forms\Components\TextInput::make('icone')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('symbole_interne')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categorie'),
                Tables\Columns\TextColumn::make('symbole_provider_externe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provider')
                    ->searchable(),
                Tables\Columns\TextColumn::make('spread')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('levier_max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_reference')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('est_actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('icone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListMarketInstruments::route('/'),
            'create' => Pages\CreateMarketInstrument::route('/create'),
            'edit' => Pages\EditMarketInstrument::route('/{record}/edit'),
        ];
    }
}
