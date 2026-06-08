<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'method',
        'status',
        'reference',
        'currency',
        'details',
        'proof_url',
        'processed_at',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:8',
            'details' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDeposit($query)
    {
        return $query->where('type', 'deposit');
    }

    public function scopeWithdraw($query)
    {
        return $query->where('type', 'withdraw');
    }

    /**
     * Check if transaction can be edited
     */
    public function canEdit(): bool
    {
        // Can only edit if not completed and not processed
        return $this->status !== 'completed' && is_null($this->processed_at);
    }

    /**
     * Check if transaction can be deleted
     */
    public function canDelete(): bool
    {
        // Can only delete if not completed and not processed
        return $this->status !== 'completed' && is_null($this->processed_at);
    }

    /**
     * Get status badge label
     */
    public function getStatusBadgeLabel(): string
    {
        return match($this->status) {
            'pending' => 'En Attente',
            'processing' => 'En Cours',
            'completed' => 'Complétée',
            'failed' => 'Échouée',
            'dispute' => 'Litige',
            default => ucfirst($this->status),
        };
    }
}
