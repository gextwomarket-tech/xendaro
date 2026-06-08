<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'trading_balance',
        'demo_balance',
        'total_deposited',
        'total_withdrawn',
        'margin_used',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'balance'          => 'decimal:8',
            'trading_balance'  => 'decimal:8',
            'demo_balance'     => 'decimal:8',
            'total_deposited'  => 'decimal:8',
            'total_withdrawn'  => 'decimal:8',
            'margin_used'      => 'decimal:8',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
