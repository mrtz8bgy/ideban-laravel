<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUSES = ['requested', 'quoted', 'accepted', 'in_progress', 'completed', 'cancelled'];

    protected $fillable = [
        'reference', 'user_id', 'lead_id', 'service_id', 'plan_id', 'status', 'progress_percent',
        'customer_note', 'staff_note', 'addon_ids', 'estimate_setup', 'estimate_recurring',
    ];

    protected $casts = ['addon_ids' => 'array', 'progress_percent' => 'integer'];

    public static function newReference(): string
    {
        do {
            $ref = 'ORD-'.strtoupper(Str::random(8));
        } while (self::where('reference', $ref)->exists());

        return $ref;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function plan()
    {
        return $this->belongsTo(PricingPlan::class, 'plan_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
