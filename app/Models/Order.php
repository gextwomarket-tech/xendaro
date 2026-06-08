<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instrument_id',
        'type',
        'direction',
        'volume',
        'price',
        'stop_loss',
        'take_profit',
        'status',
        'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'volume' => 'decimal:8',
            'price' => 'decimal:8',
            'stop_loss' => 'decimal:8',
            'take_profit' => 'decimal:8',
            'executed_at' => 'datetime',
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

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
