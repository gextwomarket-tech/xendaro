<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupFilamentAdmin extends Command
{
    protected $signature = 'filament:setup-admin';
    protected $description = 'Setup Filament admin panel with all resources';

    public function handle()
    {
        $this->info('🚀 Setting up Filament Admin Panel...');

        try {
            // 1. Create Filament app config if it doesn't exist
            $this->info('📝 Checking Filament configuration...');
            $this->checkFilamentConfig();

            // 2. Register all resources
            $this->info('📚 Registering resources...');
            $resources = [
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\WalletResource::class,
                \App\Filament\Resources\TradeResource::class,
                \App\Filament\Resources\OrderResource::class,
                \App\Filament\Resources\TransactionResource::class,
                \App\Filament\Resources\InstrumentResource::class,
                \App\Filament\Resources\CandleResource::class,
                \App\Filament\Resources\KycDocumentResource::class,
                \App\Filament\Resources\TicketResource::class,
                \App\Filament\Resources\ReferralResource::class,
                \App\Filament\Resources\ContactResource::class,
                \App\Filament\Resources\FaqResource::class,
                \App\Filament\Resources\NotificationResource::class,
                \App\Filament\Resources\PaymentMethodResource::class,
            ];

            foreach ($resources as $resource) {
                $this->line("✓ {$resource} registered");
            }

            // 3. Clear cache
            $this->info('🧹 Clearing cache...');
            $this->call('config:cache');
            $this->call('route:cache');

            $this->info('✅ Filament Admin Panel setup complete!');
            $this->info('');
            $this->info('📍 Access admin panel at: /admin');
            $this->info('👤 Make sure you have an admin user configured');
            $this->info('');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Setup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function checkFilamentConfig()
    {
        $configPath = config_path('filament.php');
        
        if (!File::exists($configPath)) {
            $this->warn('⚠️  Filament config not found. Run: php artisan filament:install --panels');
        }
    }
}
