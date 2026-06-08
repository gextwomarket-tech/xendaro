<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralResource\Pages;
use App\Models\Referral;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;
    protected static ?string $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationLabel = 'Parrainage';
    protected static ?int $navigationSort = 17;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Information')
                ->schema([
                    Forms\Components\Select::make('referrer_id')
                        ->label('Parrain')
                        ->relationship('referrer', 'email')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('referred_id')
                        ->label('Filleul')
                        ->relationship('referred', 'email')
                        ->searchable()
                        ->required(),
                ])->columns(2),
            
            Forms\Components\Section::make('Statut')
                ->schema([
                    Forms\Components\TextInput::make('status')
                        ->maxLength(50)
                        ->required(),
                ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('referrer.email')
                ->label('Parrain')
                ->searchable(),
            Tables\Columns\TextColumn::make('referred.email')
                ->label('Filleul')
                ->searchable(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'active',
                    'danger' => 'inactive',
                ]),
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
            'index' => Pages\ListReferrals::route('/'),
            'create' => Pages\CreateReferral::route('/create'),
            'edit' => Pages\EditReferral::route('/{record}/edit'),
        ];
    }
}
