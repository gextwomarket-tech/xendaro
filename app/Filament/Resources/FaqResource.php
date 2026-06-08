<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'FAQs';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Question & Réponse')
                ->schema([
                    Forms\Components\TextInput::make('question')
                        ->required()
                        ->maxLength(500),
                    Forms\Components\Textarea::make('answer')
                        ->required()
                        ->rows(6),
                ]),
            
            Forms\Components\Section::make('Organisation')
                ->schema([
                    Forms\Components\Select::make('category')
                        ->options([
                            'account' => 'Compte',
                            'trading' => 'Trading',
                            'deposits' => 'Dépôts',
                            'withdrawals' => 'Retraits',
                            'security' => 'Sécurité',
                            'other' => 'Autre',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('order')
                        ->label('Ordre d\'affichage')
                        ->numeric()
                        ->default(0),
                ])->columns(2),
            
            Forms\Components\Section::make('Statut')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->inline(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->sortable(),
            Tables\Columns\TextColumn::make('question')
                ->searchable()
                ->limit(50),
            Tables\Columns\BadgeColumn::make('category')
                ->sortable(),
            Tables\Columns\TextColumn::make('order')
                ->numeric()
                ->sortable(),
            Tables\Columns\IconColumn::make('is_active')
                ->boolean()
                ->sortable(),
        ])->filters([
            SelectFilter::make('category')
                ->options([
                    'account' => 'Compte',
                    'trading' => 'Trading',
                    'deposits' => 'Dépôts',
                    'withdrawals' => 'Retraits',
                    'security' => 'Sécurité',
                ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
        ])->defaultSort('order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
