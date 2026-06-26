<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BotBalanceUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private float $incrementAmount,
        private string $accountType,
        private float $newBalance,
        private float $profitRate,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $accountLabel = $this->accountType === 'demo' ? 'Demo Account' : 'Real Account';
        return (new MailMessage)
            ->subject('🤖 FoxBot — Hourly Profit Update')
            ->greeting('Profit du Bot Horaire!')
            ->line("Votre bot FoxBot a généré une mise à jour de profit sur votre compte $accountLabel")
            ->line("**Profit généré:** +\${$this->incrementAmount}")
            ->line("**Taux appliqué:** {$this->profitRate}%")
            ->line("**Nouveau solde:** \${$this->newBalance}")
            ->action('View Dashboard', url('/dashboard'))
            ->line('Merci d\'utiliser XFT!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $prefix = '🤖 FoxBot — ';
        $accountLabel = $this->accountType === 'demo' ? 'Demo' : 'Real';

        return [
            'type'           => 'bot_balance_update',
            'title'          => $prefix . 'Hourly Profit',
            'message'        => "Profit généré: +\${$this->incrementAmount} ({$this->profitRate}% du solde)",
            'increment_amount' => $this->incrementAmount,
            'account_type'   => $this->accountType,
            'account_label'  => $accountLabel,
            'new_balance'    => $this->newBalance,
            'profit_rate'    => $this->profitRate,
            'icon'           => '🤖',
            'color'          => 'success',
            'action_url'     => '/dashboard',
            'timestamp'      => now()->toIso8601String(),
        ];
    }
}
