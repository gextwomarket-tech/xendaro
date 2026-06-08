<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    use HasFactory;

    protected $table = 'ticket_replies';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachment_url',
        'is_admin_reply',
    ];

    protected function casts(): array
    {
        return [
            'is_admin_reply' => 'boolean',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
