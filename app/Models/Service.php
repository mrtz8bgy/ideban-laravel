<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'category_id', 'slug', 'title_fa', 'title_en', 'summary_fa', 'summary_en',
        'description_fa', 'description_en', 'included_fa', 'included_en',
        'excluded_fa', 'excluded_en', 'delivery_days', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'included_fa' => 'array',
        'included_en' => 'array',
        'excluded_fa' => 'array',
        'excluded_en' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function plans()
    {
        return $this->hasMany(PricingPlan::class);
    }

    public function prices()
    {
        return $this->hasMany(ServicePrice::class);
    }
}
