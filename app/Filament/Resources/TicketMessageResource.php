<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketMessageResource\Pages;
use App\Filament\Resources\TicketMessageResource\RelationManagers;
use App\Models\TicketMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketMessageResource extends Resource
{
    protected static ?string $model = TicketMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Espace Client';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ticket_id')
                    ->relationship('ticket', 'id')
                    ->required(),
                Forms\Components\Select::make('auteur_id')
                    ->relationship('auteur', 'name')
                    ->required(),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('est_admin')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('auteur.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('est_admin')
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
            'index' => Pages\ListTicketMessages::route('/'),
            'create' => Pages\CreateTicketMessage::route('/create'),
            'edit' => Pages\EditTicketMessage::route('/{record}/edit'),
        ];
    }
}
