<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name', 'company', 'phone', 'email', 'service_id', 'plan_id', 'source', 'message',
        'stage', 'assigned_to', 'follow_up_at', 'expected_value', 'sales_note',
    ];

    protected $casts = ['follow_up_at' => 'datetime'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function plan()
    {
        return $this->belongsTo(PricingPlan::class, 'plan_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
