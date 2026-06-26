<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Méthodes de Paiement';
    protected static ?string $modelLabel = 'Méthode de Paiement';
    protected static ?string $pluralModelLabel = 'Méthodes de Paiement';
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Identité')
                ->description('Informations d\'identification de ce moyen de paiement')
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->label('Nom affiché')
                        ->placeholder('Ex : Virement SEPA, USDT TRC20, Mobile Money Orange...')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('type')
                        ->label('Type')
                        ->options([
                            'bank_transfer'  => 'Virement bancaire',
                            'crypto_usdt'    => 'Crypto — USDT',
                            'crypto_btc'     => 'Crypto — Bitcoin',
                            'crypto_eth'     => 'Crypto — Ethereum',
                            'crypto_other'   => 'Crypto — Autre',
                            'mobile_money'   => 'Mobile Money',
                            'card'           => 'Carte bancaire',
                            'paypal'         => 'PayPal',
                            'other'          => 'Autre',
                        ])
                        ->required()
                        ->searchable(),
                ])->columns(2),

            Forms\Components\Section::make('Informations de paiement')
                ->description('Renseignez les coordonnées de ce moyen de paiement (IBAN, adresse crypto, numéro de compte, etc.)')
                ->schema([
                    Forms\Components\KeyValue::make('details')
                        ->label('Coordonnées')
                        ->keyLabel('Champ')
                        ->valueLabel('Valeur')
                        ->addButtonLabel('Ajouter une ligne')
                        ->reorderable()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('instructions')
                        ->label('Instructions de paiement')
                        ->helperText('Affichées au client lors du dépôt/retrait pour ce moyen de paiement.')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('address')
                        ->label('Adresse (crypto, IBAN…)')
                        ->helperText('Affichée avec un bouton de copie côté client.')
                        ->nullable(),

                    Forms\Components\TextInput::make('numero')
                        ->label('Numéro de compte / téléphone')
                        ->helperText('Affiché avec un bouton de copie côté client.')
                        ->nullable(),

                    Forms\Components\Textarea::make('qrcode')
                        ->label('Donnée du QR code')
                        ->helperText('Texte ou URI encodé automatiquement en QR code côté client (ex: adresse crypto, lien de paiement). Laisser vide pour ne pas afficher de QR code.')
                        ->rows(2)
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Statut')
                ->schema([
                    Forms\Components\Toggle::make('is_default')
                        ->label('Méthode par défaut')
                        ->helperText('Affichée en premier dans la liste des options de paiement')
                        ->inline(false),
                ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('label')
                ->label('Nom')
                ->searchable()
                ->sortable()
                ->weight('bold'),

            Tables\Columns\BadgeColumn::make('type')
                ->label('Type')
                ->formatStateUsing(fn (string $state): string => match($state) {
                    'bank_transfer' => 'Virement',
                    'crypto_usdt'   => 'USDT',
                    'crypto_btc'    => 'Bitcoin',
                    'crypto_eth'    => 'Ethereum',
                    'crypto_other'  => 'Crypto',
                    'mobile_money'  => 'Mobile Money',
                    'card'          => 'Carte',
                    'paypal'        => 'PayPal',
                    default         => ucfirst($state),
                })
                ->colors([
                    'success' => fn ($state) => in_array($state, ['bank_transfer', 'card']),
                    'warning' => fn ($state) => str_starts_with($state, 'crypto'),
                    'info'    => fn ($state) => $state === 'mobile_money',
                    'primary' => fn ($state) => $state === 'paypal',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('details')
                ->label('Infos')
                ->formatStateUsing(function ($state): string {
                    if (empty($state)) return '—';
                    $pairs = is_array($state) ? $state : (json_decode($state, true) ?? []);
                    return collect($pairs)
                        ->take(2)
                        ->map(fn ($v, $k) => "$k: $v")
                        ->join(' · ');
                })
                ->limit(60)
                ->tooltip(function ($record): string {
                    if (empty($record->details)) return '';
                    return collect($record->details)
                        ->map(fn ($v, $k) => "$k: $v")
                        ->join("\n");
                }),

            Tables\Columns\IconColumn::make('is_default')
                ->label('Défaut')
                ->boolean()
                ->trueIcon('heroicon-o-star')
                ->falseIcon('heroicon-o-star')
                ->trueColor('warning')
                ->falseColor('gray')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Créée')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('type')
                ->label('Type')
                ->options([
                    'bank_transfer' => 'Virement bancaire',
                    'crypto_usdt'   => 'USDT',
                    'crypto_btc'    => 'Bitcoin',
                    'crypto_eth'    => 'Ethereum',
                    'crypto_other'  => 'Crypto — Autre',
                    'mobile_money'  => 'Mobile Money',
                    'card'          => 'Carte bancaire',
                    'paypal'        => 'PayPal',
                    'other'         => 'Autre',
                ]),

            Tables\Filters\TernaryFilter::make('is_default')
                ->label('Par défaut'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->defaultSort('is_default', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit'   => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
