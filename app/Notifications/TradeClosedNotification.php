<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class TradeClosedNotification extends Notification
{
    public function __construct(
        private readonly float  $pnl,
        private readonly float  $margin,
        private readonly string $symbol,
        private readonly string $accountType,
        private readonly float  $demoBalance,
        private readonly float  $realBalance,
        private readonly bool   $isBot = false,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $isWin  = $this->pnl >= 0;
        $pnlStr = ($isWin ? '+' : '') . number_format($this->pnl, 2);
        $prefix = $this->isBot ? '🤖 FoxBot — ' : '📊 Trade — ';

        return [
            'title'        => $prefix . ($isWin ? "Gain {$pnlStr} \$" : "Perte {$pnlStr} \$"),
            'message'      => "{$this->symbol} | Mise : " . number_format($this->margin, 2) . ' $'
                            . ' | Solde démo : ' . number_format($this->demoBalance, 2) . ' $'
                            . ' | Solde réel : ' . number_format($this->realBalance, 2) . ' $',
            'pnl'          => $this->pnl,
            'margin'       => $this->margin,
            'symbol'       => $this->symbol,
            'is_win'       => $isWin,
            'is_bot'       => $this->isBot,
            'account_type' => $this->accountType,
            'demo_balance' => $this->demoBalance,
            'real_balance' => $this->realBalance,
        ];
    }
}
