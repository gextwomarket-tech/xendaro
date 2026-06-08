<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candle extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_id',
        'timeframe',
        'time',
        'open',
        'high',
        'low',
        'close',
        'volume',
    ];

    protected function casts(): array
    {
        return [
            'time' => 'datetime',
            'open' => 'decimal:8',
            'high' => 'decimal:8',
            'low' => 'decimal:8',
            'close' => 'decimal:8',
            'volume' => 'decimal:8',
        ];
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
