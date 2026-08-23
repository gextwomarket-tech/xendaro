<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KycDocumentResource\Pages;
use App\Filament\Resources\KycDocumentResource\RelationManagers;
use App\Models\KycDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KycDocumentResource extends Resource
{
    protected static ?string $model = KycDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Espace Client';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('type_document')
                    ->options([
                        'piece_identite' => 'Pièce d\'identité',
                        'justificatif_domicile' => 'Justificatif de domicile',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('fichier_path')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'refuse' => 'Refusé',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('commentaire_admin')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_document')
                    ->formatStateUsing(fn (string $state) => $state === 'piece_identite' ? 'Pièce d\'identité' : 'Justificatif de domicile'),
                Tables\Columns\TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'valide' => 'success',
                        'refuse' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'refuse' => 'Refusé',
                    ]),
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
            'index' => Pages\ListKycDocuments::route('/'),
            'create' => Pages\CreateKycDocument::route('/create'),
            'edit' => Pages\EditKycDocument::route('/{record}/edit'),
        ];
    }
}
