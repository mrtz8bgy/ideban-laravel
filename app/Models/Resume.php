<?php

namespace App\Models;

use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    public const ITEM_TYPES = [
        'experience' => ['fa' => 'سوابق کاری', 'en' => 'Work experience'],
        'education' => ['fa' => 'تحصیلات', 'en' => 'Education'],
        'skill' => ['fa' => 'مهارت‌ها', 'en' => 'Skills'],
        'certificate' => ['fa' => 'گواهینامه‌ها', 'en' => 'Certificates'],
    ];

    protected $fillable = [
        'slug', 'name_fa', 'name_en', 'job_title_fa', 'job_title_en', 'bio_fa', 'bio_en',
        'email', 'phone', 'location_fa', 'location_en', 'photo_path', 'is_sample',
        'is_published', 'sort_order',
    ];

    protected $casts = ['is_sample' => 'boolean', 'is_published' => 'boolean'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function items()
    {
        return $this->hasMany(ResumeItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function itemsOf(string $type)
    {
        return $this->items->where('type', $type)->values();
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return PublicMedia::url($this->photo_path);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
