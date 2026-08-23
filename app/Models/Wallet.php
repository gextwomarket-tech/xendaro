<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'solde_reel',
        'solde_demo',
        'devise',
    ];

    protected function casts(): array
    {
        return [
            'solde_reel' => 'decimal:2',
            'solde_demo' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function soldePour(string $mode): float
    {
        return (float) ($mode === 'reel' ? $this->solde_reel : $this->solde_demo);
    }
}
