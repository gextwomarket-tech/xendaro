<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationResourceResource\Pages;
use App\Models\EducationResource as EducationResourceModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EducationResourceResource extends Resource
{
    protected static ?string $model = EducationResourceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Académie';

    protected static ?string $modelLabel = 'Ressource pédagogique';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titre_fr')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('titre_en')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('contenu_fr')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('contenu_en')
                    ->columnSpanFull(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'nom_fr')
                    ->default(null),
                Forms\Components\Select::make('type')
                    ->options([
                        'cours' => 'Cours',
                        'glossaire' => 'Glossaire',
                        'webinaire' => 'Webinaire',
                    ])
                    ->required()
                    ->default('cours'),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\Toggle::make('est_actif')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titre_fr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('category.nom_fr')
                    ->label('Catégorie'),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\IconColumn::make('est_actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
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
            'index' => Pages\ListEducationResources::route('/'),
            'create' => Pages\CreateEducationResource::route('/create'),
            'edit' => Pages\EditEducationResource::route('/{record}/edit'),
        ];
    }
}
