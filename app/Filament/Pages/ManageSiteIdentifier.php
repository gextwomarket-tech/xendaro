<?php

namespace App\Filament\Pages;

use App\Models\SiteIdentifier;
use App\Services\SiteIdentifierService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * Page de parametrage du singleton site_identifier (branding, contact, textes legaux)
 * consomme par toutes les pages vitrine + panneau de branding auth. Pas un CRUD classique
 * (une seule ligne en base) - page Filament custom avec formulaire lie directement au modele.
 */
class ManageSiteIdentifier extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Parametres';

    protected static ?string $navigationLabel = 'Identite du site';

    protected static ?string $title = "Identite du site (site_identifier)";

    protected static string $view = 'filament.pages.manage-site-identifier';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteIdentifierService::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('SiteIdentifier')->tabs([
                    Tabs\Tab::make('Identite')->schema([
                        TextInput::make('nom_plateforme')->required()->maxLength(255),
                        TextInput::make('slogan')->maxLength(255),
                        TextInput::make('langue_par_defaut')->maxLength(5)->default('fr'),
                        ColorPicker::make('couleur_principale'),
                        ColorPicker::make('couleur_secondaire'),
                        FileUpload::make('path_light_logo')->image()->directory('site-identifier')->label('Logo (fond clair)'),
                        FileUpload::make('path_dark_logo')->image()->directory('site-identifier')->label('Logo (fond sombre)'),
                        FileUpload::make('path_favicon_png')->image()->directory('site-identifier')->label('Favicon'),
                        Textarea::make('about_us')->rows(4)->columnSpanFull(),
                        KeyValue::make('reseaux_sociaux')
                            ->label('Reseaux sociaux')
                            ->keyLabel('Reseau (ex: facebook)')
                            ->valueLabel('URL')
                            ->columnSpanFull(),
                    ])->columns(2),

                    Tabs\Tab::make('Contact')->schema([
                        TextInput::make('phone_contact_1'),
                        TextInput::make('phone_contact_2'),
                        TextInput::make('email_pro_1')->email(),
                        TextInput::make('email_pro_2')->email(),
                        TextInput::make('location_adresse')->columnSpanFull(),
                    ])->columns(2),

                    Tabs\Tab::make('Contenus legaux')->schema([
                        Section::make('CGV')->schema([Textarea::make('cvg')->rows(6)->label(false)]),
                        Section::make('Politique de confidentialite')->schema([Textarea::make('policies')->rows(6)->label(false)]),
                        Section::make('Politique de cookies')->schema([Textarea::make('cookies')->rows(6)->label(false)]),
                        Section::make('Nos services')->schema([Textarea::make('nos_services')->rows(4)->label(false)]),
                        Section::make('Contact (contenu complementaire)')->schema([Textarea::make('contact')->rows(4)->label(false)]),
                    ]),
                ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteIdentifier::query()->first()?->update($state) ?? SiteIdentifier::create($state);

        SiteIdentifierService::forget();

        Notification::make()->title('Parametres enregistres')->success()->send();
    }
}
