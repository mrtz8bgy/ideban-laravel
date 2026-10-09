<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAddon extends Model
{
    protected $fillable = [
        'service_id', 'name_fa', 'name_en', 'description_fa', 'description_en',
        'amount', 'price_type', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /** Only company-suggested or negotiated amounts are priced automatically; anything else needs a quote. */
    public function hasPublicAmount(): bool
    {
        return $this->amount !== null && in_array($this->price_type, ['company', 'negotiated'], true);
    }
}
