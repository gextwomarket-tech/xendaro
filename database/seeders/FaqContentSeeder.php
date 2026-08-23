<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FaqContent;
use Illuminate\Database\Seeder;

class FaqContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::ofType('faq')->pluck('id', 'slug');

        $faqs = [
            [
                'categorie_id' => $categories['compte-inscription'] ?? null,
                'question_fr' => 'Comment créer un compte Xendaro Fox ?',
                'reponse_fr' => "Cliquez sur \"Créer un compte\", renseignez votre nom, votre email et un mot de passe, acceptez les CGV puis validez votre adresse email via le code reçu.",
                'question_en' => 'How do I create a Xendaro Fox account?',
                'reponse_en' => 'Click "Create Account", fill in your name, email and password, accept the terms, then verify your email using the code you receive.',
                'ordre' => 1,
            ],
            [
                'categorie_id' => $categories['compte-inscription'] ?? null,
                'question_fr' => 'Dois-je vérifier mon identité (KYC) ?',
                'reponse_fr' => "Oui, une vérification d'identité est obligatoire avant tout retrait de fonds, conformément à nos obligations réglementaires.",
                'question_en' => 'Do I need to verify my identity (KYC)?',
                'reponse_en' => 'Yes, identity verification is required before any withdrawal, in line with our regulatory obligations.',
                'ordre' => 2,
            ],
            [
                'categorie_id' => $categories['depots-retraits'] ?? null,
                'question_fr' => 'Quels sont les moyens de dépôt disponibles ?',
                'reponse_fr' => 'Carte bancaire, virement bancaire, e-wallets et cryptomonnaies, selon les moyens de paiement activés par notre équipe.',
                'question_en' => 'What deposit methods are available?',
                'reponse_en' => 'Credit/debit card, bank transfer, e-wallets and cryptocurrencies, depending on the payment methods enabled by our team.',
                'ordre' => 1,
            ],
            [
                'categorie_id' => $categories['depots-retraits'] ?? null,
                'question_fr' => 'Combien de temps prend un retrait ?',
                'reponse_fr' => 'Le délai de traitement dépend du moyen de paiement choisi, généralement entre quelques minutes et 3 jours ouvrés.',
                'question_en' => 'How long does a withdrawal take?',
                'reponse_en' => 'Processing time depends on the chosen payment method, generally between a few minutes and 3 business days.',
                'ordre' => 2,
            ],
            [
                'categorie_id' => $categories['trading'] ?? null,
                'question_fr' => "Qu'est-ce qu'un compte démo ?",
                'reponse_fr' => "Un compte démo vous permet de trader avec un capital virtuel de 10 000$ pour tester la plateforme sans risque.",
                'question_en' => 'What is a demo account?',
                'reponse_en' => 'A demo account lets you trade with virtual capital of $10,000 to test the platform risk-free.',
                'ordre' => 1,
            ],
            [
                'categorie_id' => $categories['trading'] ?? null,
                'question_fr' => 'Quel effet de levier proposez-vous ?',
                'reponse_fr' => "L'effet de levier varie selon l'instrument, jusqu'à 500:1 sur le Forex. Voir la page Conditions de trading pour le détail.",
                'question_en' => 'What leverage do you offer?',
                'reponse_en' => 'Leverage varies by instrument, up to 500:1 on Forex. See the Trading Conditions page for details.',
                'ordre' => 2,
            ],
            [
                'categorie_id' => $categories['securite'] ?? null,
                'question_fr' => 'Mes fonds sont-ils en sécurité ?',
                'reponse_fr' => 'Les fonds clients sont séparés des comptes opérationnels de la société et protégés selon les standards du secteur.',
                'question_en' => 'Are my funds safe?',
                'reponse_en' => 'Client funds are held separately from the company operating accounts and protected to industry standards.',
                'ordre' => 1,
            ],
        ];

        foreach ($faqs as $faq) {
            FaqContent::updateOrCreate(
                ['question_fr' => $faq['question_fr']],
                $faq + ['est_actif' => true]
            );
        }
    }
}
