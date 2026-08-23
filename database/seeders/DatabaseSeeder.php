<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SiteIdentifierSeeder::class,
            MarketInstrumentSeeder::class,
            PaymentMethodSeeder::class,
            CategorySeeder::class,
            FaqContentSeeder::class,
            AccountTypeSeeder::class,
            PromotionSeeder::class,
            EducationResourceSeeder::class,
            NewsArticleSeeder::class,
            EconomicEventSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
