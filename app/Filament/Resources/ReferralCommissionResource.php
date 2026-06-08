<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralCommissionResource\Pages;
use App\Models\ReferralCommission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ReferralCommissionResource extends Resource
{
    protected static ?string $model = ReferralCommission::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Commissions';
    protected static ?int $navigationSort = 18;

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
                    Forms\Components\Select::make('referral_id')
                        ->label('Parrainage')
                        ->relationship('referral', 'id')
                        ->searchable(),
                ])->columns(2),
            
            Forms\Components\Section::make('Commission')
                ->schema([
                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'En Attente',
                            'paid' => 'Payée',
                            'cancelled' => 'Annulée',
                        ])
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('referrer.email')
                ->label('Parrain')
                ->searchable(),
            Tables\Columns\TextColumn::make('amount')
                ->numeric()
                ->sortable(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'paid',
                    'danger' => 'cancelled',
                ])
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])->filters([
            SelectFilter::make('status')
                ->options([
                    'pending' => 'En Attente',
                    'paid' => 'Payée',
                    'cancelled' => 'Annulée',
                ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferralCommissions::route('/'),
            'create' => Pages\CreateReferralCommission::route('/create'),
            'edit' => Pages\EditReferralCommission::route('/{record}/edit'),
        ];
    }
}
