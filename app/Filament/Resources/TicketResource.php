<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use App\Models\TicketReply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Tickets Support';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informations')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'email')
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255),
                ])->columns(2),
            
            Forms\Components\Section::make('Contenu')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Description / Message initial')
                        ->required()
                        ->rows(5),
                ]),
            
            Forms\Components\Section::make('Statut')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'open' => 'Ouvert',
                            'in_progress' => 'En Cours',
                            'closed' => 'Fermé',
                        ])
                        ->required(),
                    Forms\Components\Select::make('priority')
                        ->options([
                            'low' => 'Faible',
                            'medium' => 'Moyen',
                            'high' => 'Élevé',
                        ])
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('user.email')
                ->label('Utilisateur')
                ->searchable(),
            Tables\Columns\TextColumn::make('subject')
                ->searchable()
                ->limit(50),
            Tables\Columns\BadgeColumn::make('priority')
                ->colors([
                    'success' => 'low',
                    'warning' => 'medium',
                    'danger' => 'high',
                ]),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'info' => 'open',
                    'warning' => 'in_progress',
                    'success' => 'closed',
                ]),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])->filters([
            SelectFilter::make('status')
                ->options([
                    'open' => 'Ouvert',
                    'in_progress' => 'En Cours',
                    'closed' => 'Fermé',
                ]),
            SelectFilter::make('priority')
                ->options([
                    'low' => 'Faible',
                    'medium' => 'Moyen',
                    'high' => 'Élevé',
                ]),
        ])->actions([
            // ── Répondre au ticket (admin → client) ──────────────────
            Tables\Actions\Action::make('reply')
                ->label('Répondre')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->form([
                    Forms\Components\Textarea::make('message')
                        ->label('Message de réponse')
                        ->required()
                        ->minLength(5)
                        ->rows(4)
                        ->placeholder('Rédigez votre réponse…'),
                    Forms\Components\Select::make('new_status')
                        ->label('Mettre à jour le statut')
                        ->options([
                            'open'        => 'Laisser ouvert',
                            'in_progress' => 'En cours',
                            'closed'      => 'Fermer le ticket',
                        ])
                        ->default('in_progress'),
                ])
                ->action(function (Ticket $record, array $data): void {
                    TicketReply::create([
                        'ticket_id'      => $record->id,
                        'user_id'        => auth()->id(),
                        'message'        => $data['message'],
                        'is_admin_reply' => true,
                    ]);
                    $record->update([
                        'status'          => $data['new_status'],
                        'last_replied_at' => now(),
                    ]);
                    Notification::make()
                        ->title('Réponse envoyée')
                        ->success()
                        ->send();
                }),

            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
