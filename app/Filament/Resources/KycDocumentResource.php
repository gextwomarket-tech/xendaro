<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KycDocumentResource\Pages;
use App\Models\KycDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class KycDocumentResource extends Resource
{
    protected static ?string $model = KycDocument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Documents KYC';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informations')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'email')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('type')
                        ->options([
                            'identity' => 'Identité',
                            'address' => 'Adresse',
                            'bank' => 'Bancaire',
                            'income' => 'Revenus',
                        ])
                        ->required(),
                ])->columns(2),
            
            Forms\Components\Section::make('Document')
                ->schema([
                    Forms\Components\Select::make('side')
                        ->options(['front' => 'Avant', 'back' => 'Arrière'])
                        ->required(),
                    Forms\Components\FileUpload::make('file_url')
                        ->label('Fichier')
                        ->disk('public')
                        ->directory('kyc-documents')
                        ->required(),
                ])->columns(2),
            
            Forms\Components\Section::make('Vérification')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'En Attente',
                            'verified' => 'Vérifié',
                            'rejected' => 'Rejeté',
                        ])
                        ->required(),
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Raison du Rejet'),
                ])->columns(1),
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
            Tables\Columns\BadgeColumn::make('type')
                ->colors([
                    'info' => 'identity',
                    'success' => 'address',
                    'warning' => 'bank',
                    'danger' => 'income',
                ]),
            Tables\Columns\BadgeColumn::make('side')
                ->colors([
                    'info' => 'front',
                    'warning' => 'back',
                ]),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'verified',
                    'danger' => 'rejected',
                ]),
        ])->filters([
            SelectFilter::make('status')
                ->options([
                    'pending' => 'En Attente',
                    'verified' => 'Vérifié',
                    'rejected' => 'Rejeté',
                ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
        ])->defaultSort('created_at', 'desc');
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
