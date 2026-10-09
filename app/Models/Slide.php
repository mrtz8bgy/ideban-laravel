<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'title_fa', 'title_en', 'subtitle_fa', 'subtitle_en', 'button_text_fa', 'button_text_en',
        'button_url', 'image_path', 'is_sample', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_sample' => 'boolean', 'is_active' => 'boolean'];

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /** Uploaded slides live in public/uploads/slides; seeded samples point to public/images. */
    public function imageUrl(): string
    {
        return asset($this->image_path);
    }
}
