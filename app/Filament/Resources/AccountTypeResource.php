<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountTypeResource\Pages;
use App\Filament\Resources\AccountTypeResource\RelationManagers;
use App\Models\AccountType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountTypeResource extends Resource
{
    protected static ?string $model = AccountType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('depot_min')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('spread_min')
                    ->required()
                    ->numeric()
                    ->default(0.00000),
                Forms\Components\TextInput::make('levier_max')
                    ->required()
                    ->numeric()
                    ->default(100),
                Forms\Components\Toggle::make('swap_free')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('ordre')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('est_actif')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('depot_min')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('spread_min')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('levier_max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('swap_free')
                    ->boolean(),
                Tables\Columns\TextColumn::make('ordre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('est_actif')
                    ->boolean(),
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
            'index' => Pages\ListAccountTypes::route('/'),
            'create' => Pages\CreateAccountType::route('/create'),
            'edit' => Pages\EditAccountType::route('/{record}/edit'),
        ];
    }
}
