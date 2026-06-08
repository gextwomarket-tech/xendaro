<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'category',
        'bid',
        'ask',
        'spread',
        'change_24h',
        'change_24h_percent',
        'is_active',
        'specs',
    ];

    protected function casts(): array
    {
        return [
            'bid' => 'decimal:8',
            'ask' => 'decimal:8',
            'spread' => 'decimal:8',
            'change_24h' => 'decimal:8',
            'change_24h_percent' => 'decimal:4',
            'is_active' => 'boolean',
            'specs' => 'array',
        ];
    }

    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function candles(): HasMany
    {
        return $this->hasMany(Candle::class)->orderBy('time');
    }
}
