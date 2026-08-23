<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateCommissionResource\Pages;
use App\Filament\Resources\AffiliateCommissionResource\RelationManagers;
use App\Models\AffiliateCommission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliateCommissionResource extends Resource
{
    protected static ?string $model = AffiliateCommission::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Espace Client';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parrain_id')
                    ->relationship('parrain', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('filleul_id')
                    ->relationship('filleul', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0.00),
                Forms\Components\Select::make('statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'refuse' => 'Refusé',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('parrain.name')
                    ->label('Parrain')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('filleul.name')
                    ->label('Filleul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('montant')
                    ->money('USD')
                    ->sortable(),
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
            'index' => Pages\ListAffiliateCommissions::route('/'),
            'create' => Pages\CreateAffiliateCommission::route('/create'),
            'edit' => Pages\EditAffiliateCommission::route('/{record}/edit'),
        ];
    }
}
