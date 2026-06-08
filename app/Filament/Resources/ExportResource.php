<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExportResource\Pages;
use App\Models\Export;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class ExportResource extends Resource
{
    protected static ?string $model = Export::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Exports';
    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations Export')
                    ->description('Détails du fichier exporté')
                    ->schema([
                        Forms\Components\TextInput::make('file_name')
                            ->label('Nom du fichier')
                            ->readOnly()
                            ->required(),
                        Forms\Components\TextInput::make('file_path')
                            ->label('Chemin du fichier')
                            ->readOnly(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Statistiques')
                    ->description('Résultats de l\'export')
                    ->schema([
                        Forms\Components\TextInput::make('exported_rows')
                            ->label('Lignes exportées')
                            ->numeric()
                            ->readOnly(),
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'pending' => 'En attente',
                                'processing' => 'En cours',
                                'completed' => 'Complété',
                                'failed' => 'Échoué',
                            ])
                            ->readOnly(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Dates')
                    ->schema([
                        Forms\Components\DateTimePicker::make('started_at')
                            ->label('Démarré le')
                            ->readOnly(),
                        Forms\Components\DateTimePicker::make('finished_at')
                            ->label('Terminé le')
                            ->readOnly(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('file_name')
                    ->label('Fichier')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ])
                    ->sortable(),
                TextColumn::make('exported_rows')
                    ->label('Lignes exportées')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('started_at')
                    ->label('Démarré')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('finished_at')
                    ->label('Terminé')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'processing' => 'En cours',
                        'completed' => 'Complété',
                        'failed' => 'Échoué',
                    ]),
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
            'index' => Pages\ListExports::route('/'),
            'view' => Pages\ViewExport::route('/{record}'),
        ];
    }
}
