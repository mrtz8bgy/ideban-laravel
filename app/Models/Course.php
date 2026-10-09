<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Support\PublicMedia;

class Course extends Model
{
    public const CATEGORIES = [
        'شروع کسب‌وکار اینترنتی', 'راه‌اندازی فروشگاه آنلاین', 'WordPress', 'Laravel',
        'سرور و Linux', 'Docker و DevOps', 'امنیت وب‌سایت', 'نگهداری و پشتیبانی IT',
    ];
    public const LEVELS = ['beginner', 'intermediate', 'advanced'];

    protected $fillable = [
        'slug', 'title_fa', 'title_en', 'instructor_fa', 'instructor_en', 'summary_fa', 'summary_en',
        'description_fa', 'description_en', 'category', 'level', 'duration_minutes', 'prerequisite_fa',
        'prerequisite_en', 'price', 'is_free', 'is_published', 'cover_url', 'cover_path', 'sort_order',
    ];

    protected $casts = ['is_free' => 'boolean', 'is_published' => 'boolean'];

    /** Slug in URLs keeps course links readable and makes route generation match binding. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order')->orderBy('id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function discounts()
    {
        return $this->hasMany(DiscountCode::class);
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('is_published', true);
    }

    public function effectivePrice(): int
    {
        return $this->is_free ? 0 : (int) $this->price;
    }

    public function getCoverImageUrlAttribute()
    {
        return PublicMedia::url($this->cover_path) ?: $this->cover_url;
    }
}
