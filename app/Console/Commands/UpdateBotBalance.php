<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Wallet;
use App\Models\PlatformSetting;
use App\Models\Trade;
use App\Notifications\BotBalanceUpdateNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UpdateBotBalance extends Command
{
    protected $signature = 'bot:update-balance';
    protected $description = 'Update user balance based on active bot percentage per hour';

    public function handle(): int
    {
        try {
            $this->info('🤖 [FoxBot] Starting hourly balance update...');

            // Get bot configuration
            $setting = PlatformSetting::setting();
            $profitRatePerHour = (float) $setting->bot_profit_rate_per_hour ?? 0.5; // 0.5% par défaut

            if ($profitRatePerHour <= 0) {
                $this->warn('❌ Bot profit rate is 0 or negative. Skipping update.');
                return 0;
            }

            // Get all users with active bot
            $users = User::with('wallet')
                ->whereHas('trades', function ($q) {
                    $q->where('status', 'open')
                      ->where('is_bot', true)
                      ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
                })
                ->get();

            $totalUpdated = 0;
            $totalIncrementAmount = 0;

            foreach ($users as $user) {
                // Vérifier si le bot est réellement actif pour cet utilisateur
                $botState = Cache::get('user_' . $user->id . '_bot_state', []);
                $isBotActive = $botState['bot_active'] ?? false;

                if (!$isBotActive) {
                    continue;
                }

                // Vérifier qu'il y a des positions ouvertes actives (non expirées)
                $activeBotTrades = Trade::where('user_id', $user->id)
                    ->where('status', 'open')
                    ->where('is_bot', true)
                    ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)')
                    ->count();

                if ($activeBotTrades === 0) {
                    continue;
                }

                // Calculate increment amount
                $wallet = $user->wallet;
                $accountType = $botState['account_type'] ?? 'demo';
                $currentBalance = $accountType === 'demo' 
                    ? (float) $wallet->demo_balance 
                    : (float) $wallet->balance;

                $incrementAmount = ($currentBalance * $profitRatePerHour) / 100;
                $roundedIncrement = round($incrementAmount, 2);

                if ($roundedIncrement <= 0) {
                    continue;
                }

                // Update balance in transaction
                DB::beginTransaction();
                try {
                    if ($accountType === 'demo') {
                        $wallet->increment('demo_balance', $roundedIncrement);
                    } else {
                        $wallet->increment('balance', $roundedIncrement);
                    }

                    // Log the update
                    \Log::info('Bot balance updated', [
                        'user_id'       => $user->id,
                        'account_type'  => $accountType,
                        'previous'      => $currentBalance,
                        'increment'     => $roundedIncrement,
                        'new_balance'   => $currentBalance + $roundedIncrement,
                        'profit_rate'   => $profitRatePerHour,
                    ]);

                    // Send notification
                    try {
                        $user->notify(new BotBalanceUpdateNotification(
                            incrementAmount: $roundedIncrement,
                            accountType: $accountType,
                            newBalance: $currentBalance + $roundedIncrement,
                            profitRate: $profitRatePerHour,
                        ));
                    } catch (\Throwable $ne) {
                        \Log::warning('BotBalanceUpdateNotification failed: ' . $ne->getMessage());
                    }

                    // Update cache - mark bot as updated
                    Cache::put(
                        'user_' . $user->id . '_bot_last_update',
                        now()->timestamp,
                        3600 // 1 hour expiry
                    );

                    DB::commit();
                    $totalUpdated++;
                    $totalIncrementAmount += $roundedIncrement;

                } catch (\Throwable $e) {
                    DB::rollBack();
                    \Log::error('Bot balance update failed for user ' . $user->id, [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            if ($totalUpdated > 0) {
                $this->info("✅ Updated $totalUpdated users | Total increment: \$$totalIncrementAmount");
            } else {
                $this->info("ℹ️  No active bots to update");
            }

            return 0;

        } catch (\Throwable $e) {
            $this->error('❌ Command failed: ' . $e->getMessage());
            \Log::error('UpdateBotBalance command error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}
