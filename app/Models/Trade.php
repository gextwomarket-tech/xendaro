<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instrument_id',
        'account_type',
        'direction',
        'volume',
        'margin',
        'contract_size',
        'entry_price',
        'exit_price',
        'stop_loss',
        'take_profit',
        'profit_loss',
        'profit_loss_pips',
        'status',
        'close_reason',
        'is_bot',
        'opened_at',
        'closed_at',
        'duration_seconds',
    ];

    protected function casts(): array
    {
        return [
            'volume'           => 'decimal:8',
            'margin'           => 'decimal:8',
            'contract_size'    => 'decimal:8',
            'entry_price'      => 'decimal:8',
            'exit_price'       => 'decimal:8',
            'stop_loss'        => 'decimal:8',
            'take_profit'      => 'decimal:8',
            'profit_loss'      => 'decimal:8',
            'profit_loss_pips' => 'decimal:8',
            'is_bot'           => 'boolean',
            'opened_at'        => 'datetime',
            'closed_at'        => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}
