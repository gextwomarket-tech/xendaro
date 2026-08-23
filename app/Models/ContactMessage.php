<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'message',
        'est_traite',
    ];

    protected function casts(): array
    {
        return [
            'est_traite' => 'boolean',
        ];
    }
}
