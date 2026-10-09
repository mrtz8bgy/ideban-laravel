<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    public const CATEGORIES = ['general', 'technical', 'billing', 'project', 'hosting', 'security'];
    public const PRIORITIES = ['low', 'normal', 'high', 'urgent'];
    public const STATUSES = ['open', 'answered', 'in_progress', 'closed'];

    protected $fillable = [
        'reference', 'user_id', 'subject', 'category', 'priority', 'status', 'assigned_to',
        'first_response_at', 'closed_at', 'last_reply_at',
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'closed_at' => 'datetime',
        'last_reply_at' => 'datetime',
    ];

    public static function newReference(): string
    {
        do {
            $ref = 'TKT-'.strtoupper(Str::random(6));
        } while (self::where('reference', $ref)->exists());

        return $ref;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class)->orderBy('id');
    }
}
