<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EconomicEventResource\Pages;
use App\Filament\Resources\EconomicEventResource\RelationManagers;
use App\Models\EconomicEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EconomicEventResource extends Resource
{
    protected static ?string $model = EconomicEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('devise')
                    ->required()
                    ->maxLength(10),
                Forms\Components\Select::make('importance')
                    ->options([
                        'faible' => 'Faible',
                        'moyenne' => 'Moyenne',
                        'haute' => 'Haute',
                    ])
                    ->required()
                    ->default('moyenne'),
                Forms\Components\DateTimePicker::make('date_heure')
                    ->required(),
                Forms\Components\TextInput::make('valeur_precedente')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('valeur_prevue')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('valeur_reelle')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('devise')
                    ->searchable(),
                Tables\Columns\TextColumn::make('importance')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'haute' => 'danger',
                        'moyenne' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('date_heure')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valeur_precedente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valeur_prevue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valeur_reelle')
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
            'index' => Pages\ListEconomicEvents::route('/'),
            'create' => Pages\CreateEconomicEvent::route('/create'),
            'edit' => Pages\EditEconomicEvent::route('/{record}/edit'),
        ];
    }
}
