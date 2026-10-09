<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'course_id', 'expires_at', 'max_uses', 'used_count', 'is_active',
    ];

    protected $casts = ['expires_at' => 'datetime', 'is_active' => 'boolean'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /** Returns the discount amount in toman for a given price, or 0 when the code is not usable. */
    public function amountFor(Course $course, int $price): int
    {
        if (!$this->is_active) {
            return 0;
        }
        if ($this->course_id && $this->course_id !== $course->id) {
            return 0;
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return 0;
        }
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return 0;
        }

        $amount = $this->type === 'percent'
            ? (int) floor($price * min(100, $this->value) / 100)
            : min($price, (int) $this->value);

        return max(0, min($price, $amount));
    }
}
