<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqContentResource\Pages;
use App\Filament\Resources\FaqContentResource\RelationManagers;
use App\Models\FaqContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqContentResource extends Resource
{
    protected static ?string $model = FaqContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categorie_id')
                    ->relationship('categorie', 'id')
                    ->default(null),
                Forms\Components\TextInput::make('question_fr')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('reponse_fr')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('question_en')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('reponse_en')
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
                Tables\Columns\TextColumn::make('categorie.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_fr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('question_en')
                    ->searchable(),
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
            'index' => Pages\ListFaqContents::route('/'),
            'create' => Pages\CreateFaqContent::route('/create'),
            'edit' => Pages\EditFaqContent::route('/{record}/edit'),
        ];
    }
}
