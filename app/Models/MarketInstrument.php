<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketInstrument extends Model
{
    protected $fillable = [
        'nom',
        'symbole_interne',
        'categorie',
        'symbole_provider_externe',
        'provider',
        'spread',
        'levier_max',
        'prix_reference',
        'est_actif',
        'icone',
    ];

    protected function casts(): array
    {
        return [
            'est_actif' => 'boolean',
            'spread' => 'decimal:5',
            'prix_reference' => 'decimal:5',
        ];
    }

    public function tradeHistories(): HasMany
    {
        return $this->hasMany(TradeHistory::class);
    }
}
