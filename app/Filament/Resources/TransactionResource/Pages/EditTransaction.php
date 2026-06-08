<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\User;
use App\Models\Wallet;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected ?string $previousStatus = null;

    protected function getHeaderActions(): array
    {
        if ($this->record->status === 'completed') {
            return [];
        }

        return [
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }

    protected function beforeSave(): void
    {
        // Bloquer si déjà complétée en base (status original)
        $this->previousStatus = $this->record->getOriginal('status');

        if ($this->previousStatus === 'completed' || !is_null($this->record->getOriginal('processed_at'))) {
            Notification::make()
                ->title('Transaction déjà traitée')
                ->body('Cette transaction a déjà été traitée et ne peut plus être modifiée.')
                ->danger()
                ->send();
            $this->halt();
        }

        // Sauvegarder le destinataire dans details pour les transferts
        if (($this->data['type'] ?? '') === 'transfer' && !empty($this->data['recipient_virtual'])) {
            $details = $this->record->details ?? [];
            $details['recipient_id'] = $this->data['recipient_virtual'];
            $this->data['details'] = $details;
        }

        // Auto-définir processed_at lors du passage à completed
        if (($this->data['status'] ?? '') === 'completed') {
            $this->data['processed_at'] = now()->toDateTimeString();
        }
    }

    protected function afterSave(): void
    {
        // Mettre à jour les soldes uniquement au premier passage à completed
        if ($this->previousStatus !== 'completed' && $this->record->status === 'completed') {
            $this->applyWalletChanges();
        }

        Notification::make()
            ->title('Transaction mise à jour avec succès')
            ->success()
            ->send();
    }

    private function applyWalletChanges(): void
    {
        $tx     = $this->record->fresh();
        $amount = (float) $tx->amount;

        match ($tx->type) {
            'deposit'  => $this->applyDeposit($tx->user, $amount),
            'withdraw' => $this->applyWithdraw($tx->user, $amount),
            'transfer' => $this->applyTransfer($tx->user, $tx->details['recipient_id'] ?? null, $amount),
            default    => null,
        };
    }

    private function applyDeposit(?User $user, float $amount): void
    {
        $wallet = $user?->wallet;
        if (!$wallet) return;

        $wallet->increment('balance', $amount);
        $wallet->increment('total_deposited', $amount);
    }

    private function applyWithdraw(?User $user, float $amount): void
    {
        $wallet = $user?->wallet;
        if (!$wallet) return;

        // Ne pas descendre en dessous de zéro
        $newBalance = max(0, (float) $wallet->balance - $amount);
        $wallet->update([
            'balance'         => $newBalance,
            'total_withdrawn' => (float) $wallet->total_withdrawn + $amount,
        ]);
    }

    private function applyTransfer(?User $sender, mixed $recipientId, float $amount): void
    {
        // Débit expéditeur
        $senderWallet = $sender?->wallet;
        if ($senderWallet) {
            $newBalance = max(0, (float) $senderWallet->balance - $amount);
            $senderWallet->update([
                'balance'         => $newBalance,
                'total_withdrawn' => (float) $senderWallet->total_withdrawn + $amount,
            ]);
        }

        // Crédit destinataire
        if ($recipientId) {
            $recipient       = User::find($recipientId);
            $recipientWallet = $recipient?->wallet;
            if ($recipientWallet) {
                $recipientWallet->increment('balance', $amount);
                $recipientWallet->increment('total_deposited', $amount);
            }
        }
    }
}
