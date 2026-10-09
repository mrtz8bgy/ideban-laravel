<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\PublicMedia;

class ServiceAddon extends Model
{
    protected $fillable = [
        'service_id', 'name_fa', 'name_en', 'description_fa', 'description_en',
        'amount', 'price_type', 'is_active', 'sort_order', 'media_path',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getMediaUrlAttribute()
    {
        return PublicMedia::url($this->media_path);
    }

    /** Only company-suggested or negotiated amounts are priced automatically; anything else needs a quote. */
    public function hasPublicAmount(): bool
    {
        return $this->amount !== null && in_array($this->price_type, ['company', 'negotiated'], true);
    }
}
