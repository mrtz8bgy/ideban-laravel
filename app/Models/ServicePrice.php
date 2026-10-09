<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    protected $fillable = [
        'service_id', 'title_fa', 'title_en', 'unit_fa', 'unit_en', 'amount',
        'minimum_amount', 'maximum_amount', 'price_type', 'tariff_year', 'source_name',
        'source_url', 'is_verified', 'show_amount', 'is_active', 'valid_from', 'valid_until', 'notes',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'show_amount' => 'boolean',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function canPublishAsOfficial()
    {
        return $this->price_type !== 'official' || $this->is_verified;
    }
}
