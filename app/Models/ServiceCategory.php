<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\PublicMedia;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name_fa', 'name_en', 'slug', 'description_fa', 'description_en',
        'sort_order', 'is_active', 'media_path',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }

    public function getMediaUrlAttribute()
    {
        return PublicMedia::url($this->media_path);
    }
}
