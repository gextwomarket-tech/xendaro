<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FailedImportRowResource\Pages;
use App\Models\FailedImportRow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class FailedImportRowResource extends Resource
{
    protected static ?string $model = FailedImportRow::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';
    protected static ?string $navigationLabel = 'Erreurs Import';
    protected static ?int $navigationSort = 14;
    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de la Ligne')
                    ->description('Détails de la ligne qui a échouée')
                    ->schema([
                        Forms\Components\Select::make('import_id')
                            ->relationship('import', 'file_name')
                            ->searchable()
                            ->readOnly(),
                        Forms\Components\TextInput::make('row_number')
                            ->label('Numéro de ligne')
                            ->numeric()
                            ->readOnly(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Erreur')
                    ->schema([
                        Forms\Components\Textarea::make('error')
                            ->label('Message d\'erreur')
                            ->readOnly()
                            ->rows(4),
                        Forms\Components\Textarea::make('data')
                            ->label('Données')
                            ->readOnly()
                            ->rows(6)
                            ->hint('Données brutes de la ligne'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('import.file_name')
                    ->label('Fichier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('row_number')
                    ->label('Ligne #')
                    ->sortable(),
                TextColumn::make('error')
                    ->label('Erreur')
                    ->limit(50)
                    ->color('danger')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('import_id')
                    ->relationship('import', 'file_name')
                    ->label('Import'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFailedImportRows::route('/'),
            'view' => Pages\ViewFailedImportRow::route('/{record}'),
        ];
    }
}
