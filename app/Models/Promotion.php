<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'image',
        'date_debut',
        'date_fin',
        'est_active',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'est_active' => 'boolean',
        ];
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->where('est_active', true)
            ->where(function ($q) {
                $q->whereNull('date_fin')->orWhere('date_fin', '>=', now()->toDateString());
            });
    }
}
