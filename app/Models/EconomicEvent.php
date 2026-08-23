<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EconomicEvent extends Model
{
    protected $fillable = [
        'titre',
        'devise',
        'importance',
        'date_heure',
        'valeur_precedente',
        'valeur_prevue',
        'valeur_reelle',
    ];

    protected function casts(): array
    {
        return [
            'date_heure' => 'datetime',
        ];
    }
}
