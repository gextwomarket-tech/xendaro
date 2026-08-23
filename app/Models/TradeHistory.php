<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeHistory extends Model
{
    protected $fillable = [
        'user_id',
        'market_instrument_id',
        'mode',
        'sens',
        'type_ordre',
        'volume',
        'prix_ouverture',
        'prix_cloture',
        'stop_loss',
        'take_profit',
        'marge_utilisee',
        'profit_perte',
        'statut',
        'ouvert_le',
        'cloture_le',
    ];

    protected function casts(): array
    {
        return [
            'volume' => 'decimal:2',
            'prix_ouverture' => 'decimal:5',
            'prix_cloture' => 'decimal:5',
            'stop_loss' => 'decimal:5',
            'take_profit' => 'decimal:5',
            'marge_utilisee' => 'decimal:2',
            'profit_perte' => 'decimal:2',
            'ouvert_le' => 'datetime',
            'cloture_le' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(MarketInstrument::class, 'market_instrument_id');
    }

    /**
     * Profit/perte flottant d'une position ouverte au prix courant.
     * (prix_actuel - prix_ouverture) * volume * (sens == 'buy' ? 1 : -1)
     */
    public function calculerProfitFlottant(float $prixActuel): float
    {
        $direction = $this->sens === 'buy' ? 1 : -1;

        return round(($prixActuel - (float) $this->prix_ouverture) * (float) $this->volume * $direction, 2);
    }
}
