<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FavoriteResource\Pages;
use App\Models\Favorite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FavoriteResource extends Resource
{
    protected static ?string $model = Favorite::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Favoris';
    protected static ?int $navigationSort = 15;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'email')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('instrument_id')
                ->relationship('instrument', 'symbol')
                ->searchable()
                ->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.email')
                ->label('Utilisateur')
                ->searchable(),
            Tables\Columns\TextColumn::make('instrument.symbol')
                ->label('Instrument')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])->actions([
            Tables\Actions\DeleteAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFavorites::route('/'),
        ];
    }
}
