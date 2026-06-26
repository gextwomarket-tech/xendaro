<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlatformSetting::updateOrCreate(
            ['id' => 1],
            [
                // Platform Identity
                'platform_name' => 'Purprime Fox',
                'platform_slogan' => 'Plateforme professionnelle de trading de cryptomonnaies',
                'platform_logo' => null,
                
                // Contact Information
                'contact_email' => 'contact@moontrade.com',
                'contact_phone' => '+33 1 23 45 67 89',
                'contact_whatsapp' => '+33 6 12 34 56 78',
                'contact_telegram' => 'moontrade_support',
                
                // Location Information
                'address_line_1' => '123 Avenue des Champs',
                'address_line_2' => 'Suite 100',
                'city' => 'Paris',
                'state_province' => 'Île-de-France',
                'postal_code' => '75008',
                'country' => 'France',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                
                // Business Information
                'business_registration_number' => 'SIRET123456789',
                'business_license' => 'License-2026-001',
                'business_description' => 'Purprime Fox est une plateforme de trading de cryptomonnaies professionnelle offrant des services avancés aux traders du monde entier.',
                
                // Social Media
                'social_facebook' => 'https://facebook.com/moontrade',
                'social_twitter' => 'https://twitter.com/moontrade',
                'social_linkedin' => 'https://linkedin.com/company/moontrade',
                'social_instagram' => 'https://instagram.com/moontrade',
                'social_youtube' => 'https://youtube.com/moontrade',
                
                // Company Details
                'company_name' => 'Purprime Fox SAS',
                'company_ceo' => 'CEO Name',
                'company_cfo' => 'CFO Name',
                'company_mission' => 'Democratiser le trading de cryptomonnaies avec une plateforme sécurisée et intuitive.',
                'company_vision' => 'Devenir la plateforme leader de trading de cryptomonnaies en Europe.',
                
                // Trading Configuration
                'min_deposit' => 10,
                'max_deposit' => 1000000,
                'min_withdrawal' => 5,
                'max_withdrawal' => 1000000,
                'kyc_level_required' => 1,
                'maintenance_mode' => false,
                
                // System Configuration
                'support_email' => 'support@moontrade.com',
                'no_reply_email' => 'noreply@moontrade.com',
                'default_currency' => 'USD',
                'timezone' => 'Europe/Paris',
                
                // Terms & Conditions
                'terms_and_conditions' => 'Conditions générales d\'utilisation...',
                'privacy_policy' => 'Politique de confidentialité...',
                'cookie_policy' => 'Politique relative aux cookies...',
            ]
        );

        $this->command->info('Platform settings seeded successfully!');
    }
}
