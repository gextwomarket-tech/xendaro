<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $fillable = [
        'nom',
        'depot_min',
        'spread_min',
        'levier_max',
        'swap_free',
        'description',
        'ordre',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'swap_free' => 'boolean',
            'est_actif' => 'boolean',
            'depot_min' => 'decimal:2',
            'spread_min' => 'decimal:5',
        ];
    }
}
