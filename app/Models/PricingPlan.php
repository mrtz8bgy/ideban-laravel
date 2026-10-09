<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $fillable = [
        'service_id', 'slug', 'name_fa', 'name_en', 'description_fa', 'description_en',
        'features_fa', 'features_en', 'setup_fee', 'recurring_fee', 'recurrence_fa',
        'recurrence_en', 'price_type', 'valid_until', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features_fa' => 'array',
        'features_en' => 'array',
        'valid_until' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
