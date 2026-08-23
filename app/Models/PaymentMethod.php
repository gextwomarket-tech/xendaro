<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'nom',
        'type',
        'instructions',
        'details_paiement',
        'frais',
        'delai_traitement',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'est_actif' => 'boolean',
            'frais' => 'decimal:2',
        ];
    }
}
