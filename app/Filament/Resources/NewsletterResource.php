<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterResource\Pages;
use App\Models\Newsletter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter;

class NewsletterResource extends Resource
{
    protected static ?string $model = Newsletter::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'Newsletter';
    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Abonné')
                ->schema([
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('name')
                        ->maxLength(255),
                ])->columns(2),
            
            Forms\Components\Section::make('Statut')
                ->schema([
                    Forms\Components\Toggle::make('is_subscribed')
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
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->copyable(),
            Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\IconColumn::make('is_subscribed')
                ->boolean()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])->filters([
            TernaryFilter::make('is_subscribed')
                ->label('Abonné'),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletters::route('/'),
            'create' => Pages\CreateNewsletter::route('/create'),
            'edit' => Pages\EditNewsletter::route('/{record}/edit'),
        ];
    }
}
