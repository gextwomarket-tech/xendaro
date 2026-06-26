<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ═══════════════════════════════════════════════════════════════
        // 🤖 BOT SCHEDULING
        // ═══════════════════════════════════════════════════════════════

        /**
         * Update bot balance every hour based on configured profit rate
         * This command:
         * - Checks for active bots with open positions
         * - Calculates profit based on bot_profit_rate_per_hour from platform settings
         * - Updates user balances
         * - Sends notifications
         */
        $schedule->command('bot:update-balance')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->name('bot-balance-hourly-update')
                 ->description('Update user balances based on active bot profit rate per hour');

        // Run immediately on every hour at :00 seconds
        $schedule->command('bot:update-balance')
                 ->everyMinute()
                 ->when(function () {
                     return now()->second === 0;
                 })
                 ->withoutOverlapping(300) // 5 minute lock time
                 ->name('bot-balance-minute-sync')
                 ->description('Sync bot balance updates (runs at :00 of every minute)')
                 ->onFailure(function () {
                     \Log::error('[Scheduler] bot:update-balance failed');
                 })
                 ->onSuccess(function () {
                     \Log::debug('[Scheduler] bot:update-balance completed successfully');
                 });

        // ═══════════════════════════════════════════════════════════════
        // MAINTENANCE & CLEANUP
        // ═══════════════════════════════════════════════════════════════

        /**
         * Clean up expired cache entries
         */
        $schedule->command('cache:prune-stale-tags')
                 ->daily()
                 ->at('02:00');

        /**
         * Clean up old logs (if using daily file logging)
         */
        $schedule->command('model:prune')
                 ->daily()
                 ->at('03:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
