<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Contacts';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informations')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(20),
                ])->columns(3),
            
            Forms\Components\Section::make('Message')
                ->schema([
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('message')
                        ->required()
                        ->rows(5),
                ]),
            
            Forms\Components\Section::make('Statut')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'new' => 'Nouveau',
                            'read' => 'Lu',
                            'replied' => 'Répondu',
                            'closed' => 'Fermé',
                        ])
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->copyable(),
            Tables\Columns\TextColumn::make('subject')
                ->searchable()
                ->limit(50),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'danger' => 'new',
                    'info' => 'read',
                    'warning' => 'replied',
                    'success' => 'closed',
                ]),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])->filters([
            SelectFilter::make('status')
                ->options([
                    'new' => 'Nouveau',
                    'read' => 'Lu',
                    'replied' => 'Répondu',
                    'closed' => 'Fermé',
                ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
