<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_requests';

    protected $fillable = [
        'user_id',
        'amount',
        'fees',
        'net_amount',
        'currency',
        'payment_method',
        'status',
        'reference',
        'rejection_reason',
        'bank_account_number',
        'bank_swift',
        'bank_bic',
        'bank_account_holder',
        'bank_name',
        'crypto_type',
        'crypto_address',
        'card_number',
        'card_holder_name',
        'card_bank_name',
        'card_expiry',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that made this withdrawal request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get available payment methods
     */
    public static function paymentMethods(): array
    {
        return [
            'bank_transfer' => 'Virement bancaire',
            'cryptocurrency' => 'Cryptomonnaie',
            'card' => 'Retrait par Carte bancaire',
        ];
    }

    /**
     * Get available cryptocurrencies
     */
    public static function cryptocurrencies(): array
    {
        return [
            'bitcoin' => ['name' => 'Bitcoin', 'symbol' => 'BTC', 'logo' => 'btc'],
            'ethereum' => ['name' => 'Ethereum', 'symbol' => 'ETH', 'logo' => 'eth'],
            'litecoin' => ['name' => 'Litecoin', 'symbol' => 'LTC', 'logo' => 'ltc'],
            'ripple' => ['name' => 'Ripple (XRP)', 'symbol' => 'XRP', 'logo' => 'xrp'],
            'dogecoin' => ['name' => 'Dogecoin', 'symbol' => 'DOGE', 'logo' => 'doge'],
            'usdt' => ['name' => 'Tether (USDT)', 'symbol' => 'USDT', 'logo' => 'usdt'],
        ];
    }

    /**
     * Get status badge color
     */
    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'success',
            'cancelled' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            'completed' => 'Complétée',
            'cancelled' => 'Annulée',
            default => 'Inconnue',
        };
    }
}
