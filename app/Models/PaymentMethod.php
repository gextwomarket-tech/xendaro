<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'label',
        'details',
        'instructions',
        'qrcode',
        'numero',
        'address',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'details'    => 'array',
            'is_default' => 'boolean',
        ];
    }
}
