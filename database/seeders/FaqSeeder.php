<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['category' => 'general', 'question' => 'Comment créer un compte ?', 'answer' => 'Cliquez sur "Get Started", remplissez le formulaire en 3 étapes et vérifiez votre email.', 'order' => 1],
            ['category' => 'general', 'question' => 'Quels sont les délais de retrait ?', 'answer' => 'Virement bancaire : 1-3 jours ouvrés. Crypto : quelques minutes. Mobile Money : instantané.', 'order' => 2],
            ['category' => 'trading', 'question' => 'Quel est le levier maximum ?', 'answer' => 'Jusqu\'à 1:30 pour le Forex, 1:5 pour les cryptos et 1:20 pour les indices selon votre compte.', 'order' => 1],
            ['category' => 'kyc', 'question' => 'Pourquoi dois-je vérifier mon identité ?', 'answer' => 'La vérification KYC est obligatoire pour la conformité réglementaire AML/CFT et la sécurité de vos fonds.', 'order' => 1],
            ['category' => 'security', 'question' => 'Comment activer la 2FA ?', 'answer' => 'Rendez-vous dans Profil > Sécurité > Activer 2FA, scannez le QR code avec votre application authentificatrice.', 'order' => 1],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
