<?php

namespace Database\Seeders;

use App\Models\SiteIdentifier;
use Illuminate\Database\Seeder;

class SiteIdentifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteIdentifier::updateOrCreate(['id' => 1], [
            'nom_plateforme' => 'Xendaro Fox',
            'slogan' => 'Tradez le Forex, les Cryptos, l\'Or et plus encore, en toute confiance.',
            'langue_par_defaut' => 'fr',
            'couleur_principale' => '#F5A623',
            'couleur_secondaire' => '#5B8CFF',
            'about_us' => 'Xendaro Fox est une plateforme de trading en ligne offrant un accès aux marchés Forex, Crypto, Or/Métaux, Matières premières, Indices et Actions, avec une expérience utilisateur moderne inspirée des meilleures plateformes du marché.',
            'phone_contact_1' => '+33 1 23 45 67 89',
            'email_pro_1' => 'contact@xendarofox.com',
            'location_adresse' => 'Adresse à définir',
            'cvg' => 'Conditions Générales de Vente de Xendaro Fox — contenu à finaliser avec le client.',
            'policies' => 'Politique de confidentialité de Xendaro Fox — contenu à finaliser avec le client.',
            'cookies' => 'Politique de cookies de Xendaro Fox — contenu à finaliser avec le client.',
            'nos_services' => 'Découvrez nos types de comptes, nos plateformes de trading et nos conditions compétitives.',
            'contact' => 'Notre équipe est disponible pour répondre à toutes vos questions.',
        ]);
    }
}
