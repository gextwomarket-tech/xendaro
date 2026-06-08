<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketReplyResource\Pages;
use App\Models\TicketReply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketReplyResource extends Resource
{
    protected static ?string $model = TicketReply::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left';
    protected static ?string $navigationLabel = 'Réponses Tickets';
    protected static ?int $navigationSort = 14;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Information')
                ->schema([
                    Forms\Components\Select::make('ticket_id')
                        ->relationship('ticket', 'subject')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('admin_id')
                        ->relationship('admin', 'email')
                        ->searchable(),
                ])->columns(2),
            
            Forms\Components\Section::make('Réponse')
                ->schema([
                    Forms\Components\Textarea::make('message')
                        ->required()
                        ->rows(6),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->sortable(),
            Tables\Columns\TextColumn::make('ticket.subject')
                ->label('Ticket')
                ->searchable()
                ->limit(50),
            Tables\Columns\TextColumn::make('admin.email')
                ->label('Admin')
                ->searchable(),
            Tables\Columns\TextColumn::make('message')
                ->limit(100),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTicketReplies::route('/'),
        ];
    }
}
