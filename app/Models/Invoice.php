<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $fillable = [
        'number', 'user_id', 'order_id', 'course_id', 'type', 'status', 'items',
        'subtotal', 'discount', 'extra_costs', 'total', 'valid_until', 'paid_at', 'notes',
    ];

    protected $casts = [
        'items' => 'array',
        'valid_until' => 'date',
        'paid_at' => 'datetime',
    ];

    public static function newNumber(): string
    {
        do {
            $number = 'INV-'.now()->format('ym').'-'.strtoupper(Str::random(6));
        } while (self::where('number', $number)->exists());

        return $number;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function paidAmount(): int
    {
        return (int) $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPayable(): bool
    {
        return in_array($this->status, ['issued'], true)
            && (!$this->valid_until || $this->valid_until->endOfDay()->isFuture());
    }

    public function remainingAmount(): int
    {
        return max(0, $this->total - $this->paidAmount());
    }
}
