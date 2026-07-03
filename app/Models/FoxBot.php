<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoxBot extends Model
{
    use HasFactory;

    protected $table = 'foxbots';

    protected $fillable = [
        'name_bot',
        'description',
        'is_active',
        'percentage_win_hour',
        'percentage_lost_hour',
        'win_rate',
        'risk_per_trade',
        'tp_multiplier',
        'max_concurrent_positions',
        'min_hold_seconds',
        'max_hold_seconds',
        'total_trades',
        'total_wins',
        'total_losses',
        'total_pnl',
        'total_pnl_demo',
        'avatar_emoji',
        'strategy_label',
        'notes',
    ];

    protected $casts = [
        'is_active'                => 'boolean',
        'percentage_win_hour'      => 'decimal:4',
        'percentage_lost_hour'     => 'decimal:4',
        'win_rate'                 => 'decimal:2',
        'risk_per_trade'           => 'decimal:2',
        'tp_multiplier'            => 'decimal:2',
        'max_concurrent_positions' => 'integer',
        'min_hold_seconds'         => 'integer',
        'max_hold_seconds'         => 'integer',
        'total_trades'             => 'integer',
        'total_wins'               => 'integer',
        'total_losses'             => 'integer',
        'total_pnl'                => 'decimal:2',
        'total_pnl_demo'           => 'decimal:2',
    ];

    public function getWinRateDisplayAttribute(): string
    {
        return number_format($this->win_rate, 2) . '%';
    }

    public function getGainHourDisplayAttribute(): string
    {
        return '+' . number_format($this->percentage_win_hour, 2) . '%/h';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->is_active ? 'success' : 'danger';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
